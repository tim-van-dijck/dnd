<?php

namespace App\Policies;

use App\Models\Campaign\Campaign;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->admin && $user->active) {
            return true;
        }
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Campaign $campaign): bool
    {
        return $user->roles()->where('campaign_id', $campaign->id)->count() > 0;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Campaign $campaign): bool
    {
        return $user->roles()
                ->join('permission_role', 'roles.id', '=', 'permission_role.role_id')
                ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
                ->where([
                    'roles.campaign_id' => $campaign->id,
                    'permissions.name' => 'campaign',
                    'permission_role.edit' => 1
                ])
                ->count() > 0;
    }

    public function delete(User $user, Campaign $campaign): bool
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
