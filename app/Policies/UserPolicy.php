<?php

namespace App\Policies;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->admin && $user->active) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return AuthService::userHasCampaignPermission($user, null, 'user', 'view');
    }

    public function view(User $user, User $subjectUser)
    {
        return AuthService::userHasCampaignPermission($user, $subjectUser, 'user', 'view');
    }

    public function create(User $user)
    {
        return AuthService::userHasCampaignPermission($user, null, 'user', 'create');
    }

    public function update(User $user, User $subjectUser)
    {
        return AuthService::userHasCampaignPermission($user, $subjectUser, 'user', 'edit');
    }

    public function destroy(User $user, User $subjectUser)
    {
        return AuthService::userHasCampaignPermission($user, $subjectUser, 'user', 'destroy');
    }
}
