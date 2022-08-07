<?php

namespace App\Repositories;

use App\Models\User;
use App\Notifications\InviteUser;
use App\Notifications\InviteUserForCampaign;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRepository
{
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

    public function getByCampaign(int $campaignId, int $page, int $pageSize): LengthAwarePaginator
    {
        return User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', function ($join) use ($campaignId) {
                $join->on('roles.id', '=', 'role_user.role_id')
                    ->where('roles.campaign_id', $campaignId);
            })
            ->paginate($pageSize, ['users.*', 'roles.name AS role', 'roles.id AS role_id'], 'page[number]', $page);
    }

    public function update(User $user, array $input): User
    {
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->active = !empty($input['active']);
        $user->admin = !empty($input['admin']);
        $user->save();

        return $user;
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

    public function invite(int $campaignId, string $email, int $roleId): void
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
        $user->notify(new InviteUserForCampaign($campaignId, Auth::user()->name, $link, $newUser));
    }

    public function inviteAdmin(string $email, bool $admin): void
    {

        $user = new User();
        $user->name = '';
        $user->email = $email;
        $user->password = '';
        $user->invite_code = $this->generateInviteCode();
        $user->admin = $admin;
        $user->active = true;
        $user->save();

        $link = route('invitation', ['token' => $user->invite_code]);
        $user->notify(new InviteUser(Auth::user()->name, $link));
    }

    public function updateRole(int $campaignId, User $user, int $roleId): void
    {
        $user->revokeRoles($campaignId);
        $user->grantRole($campaignId, $roleId);
    }

    public function register(string $token, array $data): void
    {
        /** @var User $user */
        $user = User::where('invite_code', $token)->firstOrFail();
        $user->name = $data['name'];
        $user->password = Hash::make($data['password']);
        $user->invite_code = null;
        $user->email_verified_at = Carbon::now();
        $user->save();
    }

    private function generateInviteCode(): string
    {
        $codes = User::get(['invite_code'])->pluck('invite_code')->toArray();
        do {
            $inviteCode = Str::random(16);
        } while (in_array($inviteCode, $codes));

        return $inviteCode;
    }
}