<?php

namespace App\Managers;

use App\Models\Campaign\Campaign;
use App\Models\Campaign\Permission;
use App\Models\Campaign\Role;
use App\Repositories\CampaignRepository;
use Illuminate\Support\Facades\Auth;

class CampaignManager
{
    private CampaignRepository $campaignRepository;

    /**
     * CampaignManager constructor.
     */
    public function __construct()
    {
        $this->campaignRepository = new CampaignRepository();
    }

    public function create(array $data): Campaign
    {
        $campaign = $this->campaignRepository->store($data);

        $adminRole = new Role();
        $adminRole->campaign_id = $campaign->id;
        $adminRole->name = 'Admin';
        $adminRole->system = 1;
        $adminRole->save();

        $permissions = Permission::whereIn('name', ['campaign', 'character', 'location', 'quest', 'note', 'user', 'role'])
            ->get();
        foreach ($permissions as $permission) {
            $adminRole->permissions()->attach($permission->id, ['view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1]);
        }

        Auth::user()->grantRole($campaign->id, $adminRole->id);
        return $campaign;
    }
}