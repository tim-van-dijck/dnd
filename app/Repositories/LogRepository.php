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
        return Log::where('logs.campaign_id', $campaignId)
            ->leftJoin('permissions', 'logs.type', '=', 'permissions.name')
            ->leftJoin('permission_role', 'permission_role.permission_id', '=', 'permissions.id')
            ->leftJoin('role_user', 'permission_role.role_id', '=', 'role_user.role_id')
            ->leftJoin('user_permissions', function ($join) {
                $join->on('user_permissions.entity', '=', 'logs.type')
                     ->on('user_permissions.entity_id', '=', 'logs.entity_id');
            })
            ->where(function ($query) {
                $query->where('logs.user_id', Auth::user()->id)
                    ->orWhere(function ($query) {
                        $query->where(['role_user.user_id' => Auth::user()->id, 'permission_role.view' => 1]);
                    })
                    ->orWhere(function ($query) {
                        $query->where(['user_permissions.user_id' => Auth::user()->id, 'user_permissions.view' => 1]);
                    });
            })
            ->with('user')
            ->groupBy('logs.id')
            ->orderBy('logs.created_at', 'DESC')
            ->limit(10)
            ->get(['logs.*']);
    }
}