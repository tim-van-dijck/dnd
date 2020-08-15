<?php

namespace App\Http\Resources;

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
        return $class;
    }

    /**
     * @param array $proficiencies
     */
    private function formatProficiencies(array $proficiencies)
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
}