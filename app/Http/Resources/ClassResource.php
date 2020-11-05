<?php

namespace App\Http\Resources;

use App\Models\Character\CharacterClass;
use App\Models\Character\ClassLevel;
use App\Repositories\FeatureRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassResource extends JsonResource
{
    /** @var CharacterClass */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        $class = $this->resource->toArray();
        $proficiencies = $this->resource->proficiencies->toArray();
        if (!empty($proficiencies)) {
            $class['proficiencies'] = $this->formatProficiencies($proficiencies);
        }
        $levels = $this->resource->levels->toArray();
        if (!empty($levels)) {
            $class['levels'] = $this->formatLevels($levels);
        }
        if ($request->has('include') && strpos($request->query('include', ''), 'features') !== false) {
            /** @var FeatureRepository $featureRepository */
            $featureRepository = resolve(FeatureRepository::class);
            $class['features'] = $featureRepository->classFeatures($class['id'])->toArray();
        }
        if ($request->has('include') && strpos($request->query('include', ''), 'subclasses.features') !== false) {
            foreach ($class['subclasses'] as &$subclass) {
                /** @var FeatureRepository $featureRepository */
                $featureRepository = resolve(FeatureRepository::class);
                $subclass['features'] = $featureRepository->subclassFeatures($class['id'])->toArray();
            }
        }
        return $class;
    }

    /**
     * @param array $proficiencies
     */
    private function formatProficiencies(array $proficiencies): array
    {
        $result = [];
        foreach ($proficiencies as $proficiency) {
            $result[] = [
                'id' => $proficiency['id'],
                'name' => $proficiency['name'],
                'optional' => $proficiency['pivot']['optional'],
                'type' => $proficiency['type']
            ];
        }
        return $result;
    }

    /**
     * @param array $levels
     * @return array
     */
    private function formatLevels(array $levels): array
    {
        $result = [];
        /** @var ClassLevel $level */
        foreach ($levels as $level) {
            $result[$level['level']] = $level;
        }
        return $result;
    }
}