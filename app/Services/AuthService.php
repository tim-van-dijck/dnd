<?php

namespace App\Services;

use App\Models\Campaign\Permission;
use App\Models\Campaign\Role;
use App\Models\Campaign\UserPermission;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\UnauthorizedException;

class AuthService
{
    /**
     * @param User $user
     * @param Model|null $model
     * @param string $entity
     * @param string $permission
     * @return bool
     */
    public static function userHasCampaignPermission(User $user, ?Model $model, string $entity, string $permission): bool
    {
        if (!in_array($permission, ['create', 'edit', 'view', 'delete'])) {
            return false;
        }
        $campaignId = Session::get('campaign_id');
        $rolePermission = $user->roles()
            ->join('permission_role', 'roles.id', '=', 'permission_role.role_id')
            ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
            ->where([
                'roles.campaign_id' => $campaignId,
                'permissions.name' => $entity,
                "permission_role.$permission" => 1
            ])
            ->count() > 0;

        $private = $model ? ($model->private ?? false) : false;

        if ($rolePermission && !$private) {
            return true;
        } elseif ($model) {
            return self::hasOverridePermission($campaignId, $user->id, $entity, $model->id, $permission);
        }
        return false;
    }

    /**
     * @param int $campaignId
     * @param int $userId
     * @param string $entity
     * @param int $entityId
     * @param string $permission
     * @return bool
     */
    public static function hasOverridePermission(
        int $campaignId,
        int $userId,
        string $entity,
        int $entityId,
        string $permission
    ) {
        return DB::table('user_permissions')
                ->where([
                    'campaign_id' => $campaignId,
                    'user_id' => $userId,
                    'entity' => $entity,
                    'entity_id' => $entityId,
                    $permission => 1
                ])->count() > 0;
    }

    /**
     * @param int $campaignId
     * @return array
     */
    public static function campaignPermissions(int $campaignId)
    {
        $permissions = [];
        $role = Auth::user()->roles()->where('campaign_id', $campaignId)->first();
        /** @var Permission $permission */
        foreach ($role->permissions as $permission) {
            $permissions[$permission->name] = [
                'view' => $permission->pivot->view,
                'create' => $permission->pivot->create,
                'edit' => $permission->pivot->edit,
                'delete' => $permission->pivot->delete,
            ];
        }

        $exceptions = Auth::user()->permissions()->where(['campaign_id' => $campaignId])->get();
        /** @var UserPermission $exception */
        foreach ($exceptions as $exception) {
            $permissions[$exception->entity]['exceptions'][$exception->entity_id] = [
                'view' => $exception->view,
                'create' => $exception->create,
                'edit' => $exception->edit,
                'delete' => $exception->delete,
            ];
        }
        return $permissions;
    }

    /**
     * @param int $campaignId
     * @param string $entity
     * @param int $entityId
     * @param array $permissions
     *
     * @throws UnauthorizedException
     */
    public static function setCustomPermissions(int $campaignId, string $entity, int $entityId, array $permissions)
    {
        if (Auth::user()->can('create', Role::class)) {
            if (empty($permissions)) {
                self::removeUserPermissions($campaignId, $entity, $entityId);
            } else {
                self::updateUserPermissions($campaignId, $entity, $entityId, $permissions);
            }
        } else {
            throw new UnauthorizedException();
        }
    }


    /**
     * @param int $campaignId
     * @param string $entity
     * @param int $entityId
     */
    private static function removeUserPermissions(int $campaignId, string $entity, int $entityId): void
    {
        UserPermission::where([
            'campaign_id' => $campaignId,
            'entity' => $entity,
            'entity_id' => $entityId
        ])->delete();
    }

    /**
     * @param int $campaignId
     * @param string $entity
     * @param int $entityId
     * @param array $permissions
     *
     * @throws UnauthorizedException
     */
    private static function updateUserPermissions(
        int $campaignId,
        string $entity,
        int $entityId,
        array $permissions
    ): void {
        $users = User::whereIn('id', array_keys($permissions))
            ->whereHas('roles', function ($query) use ($campaignId) {
                $query->where('campaign_id', $campaignId);
            })
            ->get();
        if ($users->count() != count($permissions)) {
            throw new UnauthorizedException();
        }
        foreach ($permissions as $userId => $permissionSet) {
            $userPermission = UserPermission::firstOrNew([
                'campaign_id' => $campaignId,
                'entity' => $entity,
                'entity_id' => $entityId,
                'user_id' => $userId
            ]);
            $userPermission->view = $permissionSet['view'];
            $userPermission->create = $permissionSet['create'];
            $userPermission->edit = $permissionSet['edit'];
            $userPermission->delete = $permissionSet['delete'];
            $userPermission->save();
        }
    }

    public static function setPrivateEntity(int $campaignId, string $entity, int $entityId, $userId)
    {
        $userPermission = UserPermission::firstOrNew([
            'campaign_id' => $campaignId,
            'entity' => $entity,
            'entity_id' => $entityId,
            'user_id' => $userId
        ]);
        $userPermission->view = true;
        $userPermission->create = true;
        $userPermission->edit = true;
        $userPermission->delete = true;
        $userPermission->save();
    }
}