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

        $dmRole = new Role();
        $dmRole->campaign_id = $campaign->id;
        $dmRole->name = 'Dungeon Master';
        $dmRole->system = 1;
        $dmRole->save();

        $permissions = Permission::whereIn('name', ['campaign', 'character', 'location', 'quest', 'note', 'user', 'role', 'journal'])
            ->get();
        foreach ($permissions as $permission) {
            $dmRole->permissions()->attach($permission->id, ['view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1]);
        }

        Auth::user()->grantRole($campaign->id, $dmRole->id);
        return $campaign;
    }
}