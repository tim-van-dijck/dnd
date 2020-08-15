<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RaceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        $race = $this->resource->toArray();
        $proficiencies = $this->resource->proficiencies->toArray();
        if (!empty($proficiencies)) {
            $race['proficiencies'] = $this->formatProficiencies($proficiencies);
        }
        $subraces = $this->resource->subraces->toArray();
        if (!empty($subraces)) {
            $race['subraces'] = $this->formatSubclasses($subraces);
        }
        $languages = $this->resource->languages->toArray();
        if (!empty($languages)) {
            $race['languages'] = $this->formatLanguages($languages);
        }
        $traits = $this->resource->traits->toArray();
        if (!empty($traits)) {
            $race['traits'] = $this->formatTraits($traits);
        }
        return $race;
    }

    /**
     * @param array $proficiencies
     * @return array
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
     * @param array $languages
     * @return array
     */
    private function formatLanguages(array $languages): array
    {
        $result = [];
        foreach ($languages as $language) {
            $result[] = [
                'id' => $language['id'],
                'name' => $language['name'],
                'type' => $language['type'],
                'script' => $language['script'],
                'optional' => $language['pivot']['optional'],
            ];
        }
        return $result;
    }

    /**
     * @param array $traits
     * @return array
     */
    private function formatTraits(array $traits): array
    {
        $result = [];
        foreach ($traits as $trait) {
            $result[] = [
                'id' => $trait['id'],
                'name' => $trait['name'],
                'description' => $trait['description'],
                'optional' => $trait['pivot']['optional'],
            ];
        }
        return $result;
    }

    /**
     * @param array $subraces
     * @return array
     */
    private function formatSubclasses(array $subraces): array
    {
        foreach ($subraces as &$subrace) {
            if (!empty($subrace['proficiencies'])) {
                $subrace['proficiencies'] = $this->formatProficiencies($subrace['proficiencies']);
            }
            if (!empty($subrace['languages'])) {
                $subrace['languages'] = $this->formatLanguages($subrace['languages']);
            }
            if (!empty($subrace['traits'])) {
                $subrace['traits'] = $this->formatTraits($subrace['traits']);
            }
        }
        return $subraces;
    }
}