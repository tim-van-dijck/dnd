<?php

namespace App\Policies;

use App\Models\Campaign\Role;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->admin) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return AuthService::userHasCampaignPermission($user, null, 'role', 'view');
    }

    public function view(User $user, Role $role)
    {
        return AuthService::userHasCampaignPermission($user, $role, 'role', 'view');
    }

    public function create(User $user)
    {
        return AuthService::userHasCampaignPermission($user, null, 'role', 'create');
    }

    public function update(User $user, Role $role)
    {
        return AuthService::userHasCampaignPermission($user, $role, 'role', 'edit');
    }

    public function destroy(User $user, Role $role)
    {
        return AuthService::userHasCampaignPermission($user, $role, 'role', 'destroy');
    }
}
