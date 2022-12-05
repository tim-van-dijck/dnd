<?php

namespace App\Http\Middleware;

use App\Models\Campaign\Campaign;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ValidateCampaignId
{
    /**
     * Parse and validate the Campaign-Id header and add it to the server-side session.
     */
    public function handle(Request $request, Closure $next)
    {
        $campaignId = $request->header('Campaign-Id');
        if (!empty($campaignId)) {
            $campaign = Campaign::find($campaignId);
            if (Auth::user()->can('view', $campaign)) {
                Session::put('campaign_id', $campaignId);
            }
        }
        return $next($request);
    }
}
