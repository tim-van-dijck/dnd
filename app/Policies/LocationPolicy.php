<?php

namespace App\Policies;

use App\Models\Campaign\Location;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocationPolicy
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
        return AuthService::userHasCampaignPermission($user, null, 'location', 'view');
    }

    public function view(User $user, Location $location)
    {
        return AuthService::userHasCampaignPermission($user, $location, 'location', 'view');
    }

    public function create(User $user)
    {
        return AuthService::userHasCampaignPermission($user, null, 'location', 'create');
    }

    public function update(User $user, Location $location)
    {
        return AuthService::userHasCampaignPermission($user, $location, 'location', 'edit');
    }

    public function destroy(User $user, Location $location)
    {
        return AuthService::userHasCampaignPermission($user, $location, 'location', 'destroy');
    }
}
