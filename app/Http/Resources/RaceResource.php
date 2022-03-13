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

        if (strpos('proficiencies', $request->query('includes')) !== false) {
            $proficiencies = $this->resource->proficiencies->toArray();
            $race['proficiencies'] = $this->formatProficiencies($proficiencies);
        }
        if (strpos('subraces', $request->query('includes')) !== false) {
            $subraces = $this->resource->subraces->toArray();
            $race['subraces'] = $this->formatSubclasses($subraces);
        }
        if (strpos('languages', $request->query('includes')) !== false) {
            $languages = $this->resource->languages->toArray();
            $race['languages'] = $this->formatLanguages($languages);
        }
        if (strpos('traits', $request->query('includes')) !== false) {
            $traits = $this->resource->traits->toArray();
            $race['traits'] = $this->formatTraits($traits);
        }
        if (strpos('ability_bonuses', $request->query('includes')) !== false) {
            $race['ability_bonuses'] = $this->resource->abilities()->get();
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