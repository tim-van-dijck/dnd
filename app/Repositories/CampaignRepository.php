<?php

namespace App\Repositories;

use App\Models\Campaign\Campaign;
use Illuminate\Database\Eloquent\Collection;

class CampaignRepository
{
    const DESCRIPTION_ALLOWED_TAGS = [
        '<h1>','<h2>','<h3>','<p>','<span>','<ol>','<ul>','<li>','<b>','<em>','<a>'
    ];

    public function get(array $filters, ?int $page = 1, ?int $pageSize = 20)
    {
        $query = Campaign::query();
        if (!empty($filters['query'])) {
            $query->where('name', 'LIKE', "%{$filters['query']}%")
                ->orWhere('description', 'LIKE', "%{$filters['query']}%");
        }
        return $query->paginate($pageSize, ['*'], 'page[number]', $page);
    }

    /**
     * @param int $userId
     * @return Collection
     */
    public function getByUserId(int $userId): Collection
    {
        return Campaign::query()
            ->join('roles', 'roles.campaign_id', '=', 'campaigns.id')
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
        $campaign->settings = $data['settings'] ?? [];
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
        $campaign->settings = $data['settings'] ?? [];
        $campaign->save();

        return $campaign;
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->logs()->delete();
        $campaign->notes()->delete();
        $campaign->quests()->delete();
        $campaign->delete();
    }
}