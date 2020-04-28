<?php

namespace App\Managers;

use App\Models\Campaign\Campaign;
use App\Models\Campaign\Permission;
use App\Models\Campaign\Role;
use App\Repositories\CampaignRepository;
use Illuminate\Support\Facades\Auth;

class CampaignManager
{
    /** @var CampaignRepository */
    private $campaignRepository;

    /**
     * CampaignManager constructor.
     */
    public function __construct()
    {
        $this->campaignRepository = new CampaignRepository();
    }

    public function create(array $data): Campaign
    {
        $campaign = $this->campaignRepository->create($data);

        $adminRole = new Role();
        $adminRole->campaign_id = $campaign->id;
        $adminRole->name = 'Admin';
        $adminRole->save();

        $permissions = Permission::whereIn('name', ['campaign', 'character', 'location', 'quests', 'notes', 'users'])
            ->get();
        foreach ($permissions as $permission) {
            $adminRole->permissions()->attach($permission->id, ['view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1]);
        }

        Auth::user()->grantRole($adminRole->id);
        return $campaign;
    }
}