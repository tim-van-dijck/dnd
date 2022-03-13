<?php

namespace App\Policies;

use App\Models\Character\Character;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\Access\HandlesAuthorization;

class CharacterPolicy
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
        return AuthService::userHasCampaignPermission($user, null, 'character', 'view');
    }

    public function view(User $user, Character $character)
    {
        if ($character->owner_id == $user->id) {
            return true;
        }
        return AuthService::userHasCampaignPermission($user, $character, 'character', 'view');
    }

    public function create(User $user)
    {
        return AuthService::userHasCampaignPermission($user, null, 'character', 'create');
    }

    public function update(User $user, Character $character)
    {
        if ($character->owner_id === $user->id) {
            return true;
        }
        return AuthService::userHasCampaignPermission($user, $character, 'character', 'edit');
    }

    public function delete(User $user, Character $character)
    {
        if ($character->owner_id === $user->id) {
            return true;
        }
        return AuthService::userHasCampaignPermission($user, $character, 'character', 'delete');
    }
}
