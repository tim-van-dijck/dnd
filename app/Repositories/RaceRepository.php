<?php


namespace App\Repositories;


use App\Models\Character\Race;
use Illuminate\Database\Eloquent\Collection;

class RaceRepository
{
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