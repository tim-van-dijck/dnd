<?php

namespace App\Repositories\Campaign;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SearchRepository
{
    public function search(int $campaignId, string $queryString): Collection
    {
        $chars = DB::table('characters')
            ->selectRaw('characters.id AS id, characters.name AS label, type AS type')
            ->where('campaign_id', $campaignId)
            ->where('characters.name', 'LIKE', "%$queryString%");

        $locations = DB::table('locations')
            ->selectRaw('locations.id AS id, locations.name AS label, "location" AS type')
            ->where('campaign_id', $campaignId)
            ->where('locations.name', 'LIKE', "%$queryString%");

        $notes = DB::table('notes')
            ->selectRaw('notes.id AS id, notes.name AS label, "note" AS type')
            ->where('campaign_id', $campaignId)
            ->where(function ($query) use ($queryString) {
                $query->where('notes.name', 'LIKE', "%$queryString%")
                    ->orWhere('notes.content', 'LIKE', "%$queryString%");
            });

        $quests = DB::table('quests')
            ->selectRaw('quests.id AS id, quests.title AS label, "quest" AS type')
            ->where('campaign_id', $campaignId)
            ->where(function ($query) use ($queryString) {
                $query->where('quests.title', 'LIKE', "%$queryString%")
                    ->orWhere('quests.description', 'LIKE', "%$queryString%");
            });

        return $chars->union($locations)->union($notes)->union($quests)->get();
    }
}