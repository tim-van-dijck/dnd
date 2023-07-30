<?php

namespace App\Repositories;

use App\Models\InviteCode;
use App\Models\User;
use App\Notifications\InviteUser;
use App\Notifications\InviteUserForCampaign;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            $user = new User();
            $user->name = '';
            $user->email = $email;
            $user->password = '';
            $user->save();
        }

        $user->grantRole($campaignId, $roleId);

        $link = $this->generateInvite($user, $campaignId);
        $user->notify(new InviteUserForCampaign($campaignId, Auth::user()->name, $link, $newUser));
    }

    public function inviteAdmin(string $email, bool $admin): void
    {
        $user = new User();
        $user->name = '';
        $user->email = $email;
        $user->password = '';
        $user->admin = $admin;
        $user->active = true;
        $user->save();

        $inviteLink = $this->generateInvite($user, null);
        $user->notify(new InviteUser(Auth::user()->name, $inviteLink));
    }

    public function updateRole(int $campaignId, User $user, int $roleId): void
    {
        $user->revokeRoles($campaignId);
        $user->grantRole($campaignId, $roleId);
    }

    public function register(InviteCode $code, array $data): void
    {
        $user = $code->user;

        $user->name = $data['name'];
        $user->password = Hash::make($data['password']);
        $user->invite_code = null;
        $user->email_verified_at = Carbon::now();
        $user->save();

        $code->delete();
    }

    private function generateInvite(User $user, ?string $campaignId): string
    {
        $inviteCode = new InviteCode();
        $inviteCode->campaign_id = $campaignId;
        $user->invites()->save($inviteCode);

        return route('invitation', ['token' => $inviteCode->id]);
    }
}