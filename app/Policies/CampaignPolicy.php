<?php

namespace App\Policies;

use App\Models\Campaign\Campaign;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any app models campaign campaigns.
     *
     * @param  User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the app models campaign campaign.
     *
     * @param  User $user
     * @param  Campaign $campaign
     * @return mixed
     */
    public function view(User $user, Campaign $campaign)
    {
        return $user->roles()->where('campaign_id', $campaign->id)->count() > 0;
    }

    /**
     * Determine whether the user can create app models campaign campaigns.
     *
     * @param  User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the app models campaign campaign.
     *
     * @param  User $user
     * @param  Campaign $campaign
     * @return mixed
     */
    public function update(User $user, Campaign $campaign)
    {
        return $user->roles()
            ->join('permission_role', 'role.id', '=', 'permission_role.role_id')
            ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
            ->where([
                'roles.campaign_id' => $campaign->id,
                'permissions.name' => 'campaign',
                'permission_role.edit' => 1
            ])
            ->count() > 0;
    }

    /**
     * Determine whether the user can delete the app models campaign campaign.
     *
     * @param  User $user
     * @param  Campaign $campaign
     * @return mixed
     */
    public function delete(User $user, Campaign $campaign)
    {
        return $user->roles()
            ->join('permission_role', 'role.id', '=', 'permission_role.role_id')
            ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
            ->where([
                'roles.campaign_id' => $campaign->id,
                'permissions.name' => 'campaign',
                'permission_role.delete' => 1
            ])
            ->count() > 0;
    }
}
