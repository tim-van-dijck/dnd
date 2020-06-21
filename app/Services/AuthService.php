<?php

namespace App\Services;

use App\Models\Campaign\Permission;
use App\Models\Campaign\UserPermission;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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

        $private = $model->private ?? false;
        if ($rolePermission && !$private) {
            return true;
        }
        if ($model) {
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
        $exceptions = Auth::user()->permissions()->where(['campaign_id' => $campaignId])->get();
        /** @var Permission $permission */
        foreach ($role->permissions as $permission) {
            $permissions[$permission->name] = [
                'view' => $permission->pivot->view,
                'create' => $permission->pivot->create,
                'edit' => $permission->pivot->edit,
                'delete' => $permission->pivot->delete,
            ];
        }

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
}