<?php

namespace App\Repositories;

use App\Models\Campaign\Log;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class LogRepository
{
    public function store(int $campaignId, string $type, int $entityId, string $name, string $action)
    {
        $log = new Log();
        $log->campaign_id = $campaignId;
        $log->user_id = Auth::user()->id;
        $log->type = $type;
        $log->entity = $name;
        $log->entity_id = $entityId;
        $log->action = $action;
        $log->save();
    }

    /**
     * @param int $campaignId
     * @return Collection
     */
    public function recentActivity(int $campaignId): Collection
    {
        return Log::where('campaign_id', $campaignId)
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get();
    }
}