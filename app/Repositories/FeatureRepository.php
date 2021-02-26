<?php

namespace App\Repositories;

use App\Models\Character\CharacterClass;
use App\Models\Character\Feature;
use App\Models\Character\Subclass;
use Illuminate\Database\Eloquent\Collection;

class FeatureRepository
{
    /**
     * @param int $classId
     * @return Collection
     */
    public function classFeatures(int $classId): Collection
    {
        $features = Feature::leftJoin('feature_choices AS fc', 'fc.choice_id', '=', 'features.id')
            ->join('feature_morph AS fm', 'fm.feature_id', '=', 'features.id')
            ->where([
                'fm.entity_type' => CharacterClass::class,
                'fm.entity_id' => $classId,
                'optional' => 0
            ])
            ->whereNull('fc.id')
            ->get(['features.*', 'fm.level', 'fm.choose']);

        foreach ($features as &$feature) {
            if ($feature->choose > 0) {
                $choices = Feature::join('feature_choices AS fc', 'fc.choice_id', '=', 'features.id')
                    ->join('feature_morph AS fm', 'fm.feature_id', '=', 'features.id')
                    ->where([
                        'fm.entity_type' => CharacterClass::class,
                        'fm.entity_id' => $classId,
                        'fc.entity' => CharacterClass::class,
                        'fc.entity_id' => $classId,
                        'fc.feature_id' => $feature->id,
                        'optional' => 1
                    ])
                    ->groupBy(['features.id', 'fm.level'])
                    ->get(['features.*', 'fm.level']);
                $feature->choices = $choices;
            }
        }
        return $features;
    }

    /**
     * @param int $subclassId
     * @return Collection
     */
    public function subclassFeatures(int $subclassId): Collection
    {
        $features = Feature::leftJoin('feature_choices AS fc', 'fc.choice_id', '=', 'features.id')
            ->join('feature_morph AS fm', 'fm.feature_id', '=', 'features.id')
            ->where([
                'fm.entity_type' => Subclass::class,
                'fm.entity_id' => $subclassId,
                'optional' => 0
            ])
            ->whereNull('fc.id')
            ->get(['features.*', 'fm.level']);
        foreach ($features as &$feature) {
            $choices = Feature::join('feature_choices AS fc', 'fc.choice_id', '=', 'features.id')
                ->join('feature_morph AS fm', 'fm.feature_id', '=', 'features.id')
                ->where([
                    'fm.entity_type' => Subclass::class,
                    'fm.entity_id' => $subclassId,
                    'fc.entity' => Subclass::class,
                    'fc.entity_id' => $subclassId,
                    'fc.feature_id' => $feature->id,
                    'optional' => 1
                ])
                ->groupBy(['features.id', 'fm.level'])
                ->get(['features.*', 'fm.level']);
            $feature->choices = $choices;
        }
        return $features;
    }
}
