<?php

namespace App\Repositories;

use App\Models\Campaign\Campaign;
use Illuminate\Database\Eloquent\Collection;

class CampaignRepository
{
    const DESCRIPTION_ALLOWED_TAGS = [
        '<h1>','<h2>','<h3>','<p>','<span>','<ol>','<ul>','<li>','<b>','<em>','<a>'
    ];

    /**
     * @param int $userId
     * @return Collection
     */
    public function index(int $userId): Collection
    {
        return Campaign::join('roles', 'roles.campaign_id', '=', 'campaigns.id')
            ->join('role_user', 'role_user.role_id', '=', 'roles.id')
            ->where('role_user.user_id', $userId)
            ->get(['campaigns.*', 'roles.name AS role']);
    }

    /**
     * @param array $data
     * @return Campaign
     */
    public function store(array $data): Campaign
    {
        $campaign = new Campaign();
        $campaign->name = $data['name'];
        $campaign->description = strip_tags($data['description'] ?? '', implode('', self::DESCRIPTION_ALLOWED_TAGS));
        $campaign->save();

        return $campaign;
    }

    /**
     * @param Campaign $campaign
     * @param array $data
     * @return Campaign
     */
    public function update(Campaign $campaign, array $data): Campaign
    {
        $campaign->name = $data['name'];
        $campaign->description = $data['description'];
        $campaign->save();

        return $campaign;
    }

    public function destroy()
    {

    }
}