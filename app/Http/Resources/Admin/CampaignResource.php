<?php

namespace App\Http\Resources\Admin;

use App\Models\Campaign\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $campaignArray = parent::toArray($request);
        /** @var Role $adminRole */
        $adminRole = $this->resource->roles()->where('name', 'Admin')->first();
        $campaignArray['admins'] = $adminRole->users()->get(['users.id', 'users.name']);
        return $campaignArray;
    }
}
