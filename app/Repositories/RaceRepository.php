<?php

namespace App\Repositories;

use App\Models\Character\Race;
use App\Models\Character\RaceTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class RaceRepository
{
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

    public function find(int $raceId): Race
    {
        return Race::with('subraces')->findOrFail($raceId);
    }

    public function store(array $input): Race
    {
        $input['optional_traits'] = 0;
        $race = new Race($input);
        $race->save();

        $abilityBonuses = array_map(
            fn ($ability) => [
                'race_id' => $race->id,
                'subrace_id' => null,
                'ability' => $ability['ability'],
                'bonus' => $ability['bonus'],
                'optional' => !empty($ability['optional'])
            ],
            $input['ability_bonuses']
        );
        $race->abilities()->insert($abilityBonuses);

        $this->syncRelationship('languages', $race, $input['languages']);
        $this->syncRelationship('proficiencies', $race, $input['languages']);
        $this->syncTraits($race, $input['traits']);

        return $race;
    }

    public function update(Race $race, array $input): Race
    {
        $input['optional_traits'] = 0;
        $race->fill($input);
        $race->save();

        $this->syncAbilityBonuses($race, $input['ability_bonuses']);

        $this->syncRelationship('languages', $race, $input['languages']);
        $this->syncRelationship('proficiencies', $race, $input['languages']);
        $this->syncTraits($race, $input['traits']);

        return $race;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function syncRelationship(string $relationship, Race $race, array $related): void
    {
        $toSync = [];
        foreach ($related as $relation) {
            $toSync[$relation['id']] = [
                'optional' => !empty($relation['optional']),
            ];
        }
        if (method_exists($race, $relationship)) {
            $race->$relationship()->sync($toSync);
        } else {
            throw new InvalidArgumentException("Trying to sync invalid relationship");
        }
    }

    private function syncTraits(Race $race, array $traits): void
    {
        $toCreate = array_filter($traits, fn ($trait) => empty($trait['id']));
        foreach ($toCreate as &$trait) {
            $raceTrait = new RaceTrait();
            $raceTrait->name = $trait['name'];
            $raceTrait->description = $trait['description'];
            $raceTrait->save();
            $trait['id'] = $raceTrait->id;
        }
        $this->syncRelationship('traits', $race, $traits);
    }

    public function destroy(Race $race): void
    {
        $repository = resolve(SubraceRepository::class);
        foreach ($race->subraces as $subrace) {
            $repository->destroy($subrace->id);
        }
        $race->abilities()->delete();
        $race->languages()->sync([]);
        $race->proficiencies()->sync([]);
        $race->traits()->sync([]);
        $race->delete();
    }

    private function syncAbilityBonuses(Race $race, array $abilityBonuses)
    {
        $existing = $race->abilities()->get(['ability']);
        $toCreate = array_filter(array_map(
            fn ($ability) => [
                'race_id' => $race->id,
                'subrace_id' => null,
                'ability' => $ability['ability'],
                'bonus' => $ability['bonus'],
                'optional' => !empty($ability['optional'])
            ],
            $abilityBonuses
        ), fn ($ability) => !in_array($ability['ability'], $existing->pluck('ability')->toArray()));
        $race->abilities()->insert($toCreate);
        $race->abilities()
            ->whereNotIn('ability', array_map(fn ($ability) => $ability['ability'], $abilityBonuses))
            ->delete();
    }
}