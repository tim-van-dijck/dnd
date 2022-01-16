<?php

namespace App\Repositories;

use App\Models\User;
use App\Notifications\InviteUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRepository
{
    /**
     * @param array $filters
     * @param int $page
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function get(array $filters, int $page, int $pageSize): LengthAwarePaginator
    {
        $query = User::query();
        if (!empty($filters['query'])) {
            $query->where(function ($query) use ($filters) {
                return $query->where('name', 'LIKE', "%{$filters['query']}%")
                    ->orWhere('name', 'LIKE', "%{$filters['query']}%");
            });
        }
        return $query->paginate($pageSize, ['*'], 'page[number]', $page);
    }

    /**
     * @param int $campaignId
     * @param int $page
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByCampaign(int $campaignId, int $page, int $pageSize): LengthAwarePaginator
    {
        return User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', function ($join) use ($campaignId) {
                $join->on('roles.id', '=', 'role_user.role_id')
                    ->where('roles.campaign_id', $campaignId);
            })
            ->paginate($pageSize, ['users.*', 'roles.name AS role', 'roles.id AS role_id'], 'page[number]', $page);
    }

    public function userExistsInCampaign(int $campaignId, int $userId): bool
    {
        return User::query()
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', function ($join) use ($campaignId) {
                $join->on('roles.id', '=', 'role_user.role_id')
                    ->where('roles.campaign_id', $campaignId);
            })
            ->exists();
    }

    /**
     * @param int $campaignId
     * @param string $email
     * @param int $roleId
     */
    public function invite(int $campaignId, string $email, int $roleId)
    {
        $user = User::where('email', $email)->first();
        $newUser = empty($user);
        if ($newUser) {
            $codes = User::get(['invite_code'])->pluck('invite_code')->toArray();
            $user = new User();
            $user->name = '';
            $user->email = $email;
            $user->password = '';
            do {
                $user->invite_code = Str::random(16);
            } while (in_array($user->invite_code, $codes));
            $user->save();
        }
        $user->grantRole($campaignId, $roleId);
        $link = route('invitation', ['token' => $user->invite_code]);
        $user->notify(new InviteUser($campaignId, Auth::user()->name, $link, $newUser));
    }

    /**
     * @param int $campaignId
     * @param User $user
     * @param int $roleId
     */
    public function updateRole(int $campaignId, User $user, int $roleId)
    {
        $user->revokeRoles($campaignId);
        $user->grantRole($campaignId, $roleId);
    }

    /**
     * @param string $token
     * @param array $data
     */
    public function register(string $token, array $data)
    {
        /** @var User $user */
        $user = User::where('invite_code', $token)->firstOrFail();
        $user->name = $data['name'];
        $user->password = Hash::make($data['password']);
        $user->invite_code = null;
        $user->email_verified_at = Carbon::now();
        $user->save();
    }
}