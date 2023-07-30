<?php

namespace App\Http\Resources;

use App\Services\AuthService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CurrentUserResource extends JsonResource
{
    public function toArray($request)
    {
        $campaignId = Session::get('campaign_id') ?? $request->header('Campaign-Id');
        $permissions = empty($campaignId) ? [] : AuthService::campaignPermissions($campaignId);
        return [
            'id' => Auth::user()->id,
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'permissions' => $permissions,
            'roles' => Auth::user()->roles
        ];
    }
}
