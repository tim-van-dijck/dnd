<?php

namespace App\Services\Character\Helpers;

use App\Models\Character\Character;
use App\Models\Character\CharacterClass;
use App\Models\Character\Feature;
use App\Models\Character\Subclass;
use Illuminate\Database\Eloquent\Collection;

class CharacterFeatureHelper
{
    public static function sync(Character $character, array $classes)
    {
        $sync = [];
        foreach ($classes as $class) {
            foreach ($class['features'] as $featureId => $choices) {
                foreach ($choices as $choiceId) {
                    $sync[$choiceId] = [
                        'feature_parent_id' => $featureId,
                        'entity_id' => $class['class_id'],
                        'entity_type' => CharacterClass::class
                    ];
                }
            }
        }

        $character->features()->sync($sync);
    }

    public static function getCharacterClassFeatures(int $characterId, int $classId, int $level, ?int $subclassId = null)
    {
        $classFeatures = Feature::query()
            ->join('feature_morph', 'features.id', '=', 'feature_morph.feature_id')
            ->where([
                'entity_id' => $classId,
                'entity_type' => CharacterClass::class,
                'optional' => 0
            ])
            ->where('level', '<=', $level)
            ->orderBy('level')
            ->get(['features.*', 'feature_morph.level', 'feature_morph.choose']);

        if ($subclassId) {
            $classFeatures = $classFeatures->merge(self::getSubclassFeatures($subclassId, $level));
        }

        $chosenFeatures = Feature::query()
            ->join('character_feature', 'features.id', '=', 'character_feature.feature_id')
            ->where([
                'features.optional' => 1,
                'character_id' => $characterId
            ])
            ->whereIn('character_feature.feature_parent_id', $classFeatures->pluck('id')->toArray())
            ->get(['features.*', 'character_feature.feature_parent_id']);

        return $classFeatures->map(function ($feature) use ($chosenFeatures) {
            $featureArray = $feature->toArray();
            if ($feature->choose > 0) {
                $featureArray['choices'] = $chosenFeatures->filter(function ($choice) use ($feature) {
                    return $choice->feature_parent_id == $feature->id;
                })
                    ->toArray();
            }
            return $featureArray;
        });
    }

    public static function getSubclassFeatures(int $subclassId, int $level): Collection
    {
        return Feature::query()
            ->join('feature_morph', 'features.id', '=', 'feature_morph.feature_id')
            ->where([
                'entity_id' => $subclassId,
                'entity_type' => Subclass::class,
                'optional' => 0
            ])
            ->where('level', '<=', $level)
            ->orderBy('level')
            ->get(['features.*', 'feature_morph.level', 'feature_morph.choose']);
    }
}
