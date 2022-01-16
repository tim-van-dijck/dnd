<?php


namespace App\Repositories;


use App\Models\Character\Race;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class RaceRepository
{
    /**
     * @param array $includes
     * @return LengthAwarePaginator
     */
    public function search(array $includes, int $page = 1, int $pageSize = 20, $sort = 'name'): LengthAwarePaginator
    {
        if (in_array('subraces', $includes)) {
            $includes[] = 'subraces.languages';
            $includes[] = 'subraces.proficiencies';
            $includes[] = 'subraces.traits';
        }
        return Race::with(array_unique($includes))
            ->orderBy($sort)
            ->paginate($pageSize, ['*'], 'page[number]', $page);
    }

    /**
     * @param array $includes
     * @return Collection|Race[]
     */
    public function get(array $includes): Collection
    {
        if (in_array('subraces', $includes)) {
            $includes[] = 'subraces.languages';
            $includes[] = 'subraces.proficiencies';
            $includes[] = 'subraces.traits';
        }
        return Race::with(array_unique($includes))->get();
    }

    /**
     * @param int $raceId
     * @return Race
     */
    public function find(int $raceId): Race
    {
        return Race::with('subraces')->findOrFail($raceId);
    }
}