<?php

namespace App\Http\Resources;

use App\Models\Character\ClassLevel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassResource extends JsonResource
{
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