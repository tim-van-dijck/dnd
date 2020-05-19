<?php

namespace App\Policies;

use App\Models\Character\Character;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\Access\HandlesAuthorization;

class CharacterPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user)
    {
        return AuthService::userHasCampaignPermission($user, null, 'character', 'view');
    }

    public function view(User $user, Character $character)
    {
        return AuthService::userHasCampaignPermission($user, $character, 'character', 'view');
    }

    public function create(User $user, Character $character)
    {
        return AuthService::userHasCampaignPermission($user, $character, 'character', 'create');
    }

    public function update(User $user, Character $character)
    {
        return AuthService::userHasCampaignPermission($user, $character, 'character', 'edit');
    }

    public function delete(User $user, Character $character)
    {
        return AuthService::userHasCampaignPermission($user, $character, 'character', 'delete');
    }
}
