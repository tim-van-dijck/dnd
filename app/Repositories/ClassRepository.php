<?php

namespace App\Repositories;

use App\Models\Character\Character;
use App\Models\Character\CharacterClass;
use App\Models\Character\Feature;

class ClassRepository
{
    public function get(array $includes)
    {
        $includes = array_unique(array_merge(['levels', 'subclasses.levels'], $includes));
        return CharacterClass::with($includes)->get();
    }

    public function getByCharacter(Character $character)
    {
        $classes = [];
        foreach ($character->classes as $charClass) {
            $features = Feature::query()
                ->join('feature_choices AS fc', 'fc.choice_id', '=', 'features.id')
                ->join('character_feature AS cf', 'cf.feature_id', '=', 'features.id')
                ->where([
                    'cf.character_id' => $this->resource->id,
                    'cf.entity_type' => CharacterClass::class,
                    'cf.entity_id' => $charClass->id
                ])
                ->whereNotNull('cf.feature_parent_id')
                ->get(['features.*', 'cf.feature_parent_id'])
                ->groupBy('cf.feature_parent_id');

            $classes[] = [
                'id' => $charClass->id,
                'name' => $charClass->name,
                'subclass' => $this->getSubclass($character, $charClass->pivot->subclass_id),
                'level' => $charClass->pivot->level,
                'features' => $features
            ];
        }
        return $classes;
    }

    private function getSubclass(Character $character, $subclassId)
    {
        if (empty($subclassId)) {
            return null;
        }

    }
}