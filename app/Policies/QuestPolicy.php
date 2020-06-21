<?php

namespace App\Policies;

use App\Models\Campaign\Quest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return AuthService::userHasCampaignPermission($user, null, 'quest', 'view');
    }

    public function view(User $user, Quest $quest)
    {
        return AuthService::userHasCampaignPermission($user, $quest, 'quest', 'view');
    }

    public function create(User $user)
    {
        return AuthService::userHasCampaignPermission($user, null, 'quest', 'create');
    }

    public function update(User $user, Quest $quest)
    {
        return AuthService::userHasCampaignPermission($user, $quest, 'quest', 'edit');
    }

    public function destroy(User $user, Quest $quest)
    {
        return AuthService::userHasCampaignPermission($user, $quest, 'quest', 'destroy');
    }
}
