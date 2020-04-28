<?php

namespace App\Repositories;

use App\Models\Campaign\Campaign;

class CampaignRepository
{
    const DESCRIPTION_ALLOWED_TAGS = [
        '<h1>','<h2>','<h3>','<p>','<span>','<ol>','<ul>','<li>','<b>','<em>','<a>'
    ];
    public function index(int $userId)
    {
        return Campaign::join('roles', 'roles.campaign_id', '=', 'campaigns.id')
            ->join('role_user', 'role_user.role_id', '=', 'roles.id')
            ->where('role_user.user_id', $userId)
            ->get(['campaigns.*']);
    }

    public function create(array $data)
    {
        $campaign = new Campaign();
        $campaign->name = $data['name'];
        $campaign->description = strip_tags($data['description'] ?? '', implode('', self::DESCRIPTION_ALLOWED_TAGS));
        $campaign->save();

        return $campaign;
    }
}