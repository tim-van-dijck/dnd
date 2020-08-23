<?php

namespace App\Http\Resources;

use App\Models\Character\Character;
use App\Models\Character\CharacterClass;
use App\Models\Character\Proficiency;
use App\Models\Character\Race;
use App\Models\Character\Subclass;
use App\Models\Character\Subrace;
use Illuminate\Http\Resources\Json\JsonResource;

class CharacterResource extends JsonResource
{
    /** @var Character */
    public $resource;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $character = [
            'id' => $this->resource->id,
            'info' => [
                'name' => $this->resource->name,
                'title' => $this->resource->title,
                'type' => $this->resource->type,
                'age' => $this->resource->age,
                'alignment' => $this->resource->alignment,
                'dead' => $this->resource->dead ?? false,
                'private' => $this->resource->private ?? false,
                'bio' => $this->resource->bio,
            ],
            'ability_scores' => $this->resource->ability_scores
        ];

        if ($this->resource->type == 'player') {
            $character['personality'] = [
                'trait' => $this->resource->trait,
                'ideal' => $this->resource->ideal,
                'bond' => $this->resource->bond,
                'flaw' => $this->resource->flaw
            ];
        }

        if ($this->resource->classes) {
            $character['classes'] = [];
            foreach ($this->resource->classes as $charClass) {
                $character['classes'][] = [
                    'id' => $charClass->id,
                    'name' => $charClass->name,
                    'subclass' => $charClass->subclasses->find($charClass->pivot->subclass_id),
                    'level' => $charClass->pivot->level,
                ];
            }
        }
        if ($this->resource->race) {
            $character['race'] = [
                'id' => $this->resource->race->id,
                'name' => $this->resource->race->name,
                'speed' => $this->resource->race->speed,
                'size' => $this->resource->race->size,
            ];
        }
        if ($this->resource->race && $this->resource->subrace) {
            $character['race']['subrace'] = [
                'id' => $this->resource->subrace->id,
                'name' => $this->resource->subrace->name,
                'description' => $this->resource->subrace->description
            ];
        }
        if ($this->resource->languages) {
            if (!array_key_exists('proficiencies', $character)) {
                $character['proficiencies'] = [];
            }
            $character['proficiencies']['languages'] = $this->resource->languages->pluck('id')->toArray();
        }
        if ($this->resource->proficiencies) {
            if (!array_key_exists('proficiencies', $character)) {
                $character['proficiencies'] = [];
            }
            /** @var Proficiency $proficiency */
            foreach ($this->resource->proficiencies as $proficiency) {
                switch ($proficiency->pivot->origin_type) {
                    case CharacterClass::class:
                    case Subclass::class:
                        $origin = 'Class';
                        break;
                    case Race::class:
                    case Subrace::class:
                        $origin = 'Race';
                        break;
                    default:
                        $origin = 'Unknown origin';
                        break;
                }
                $character['proficiencies'][strtolower($proficiency->type)][] = [
                    'id' => $proficiency->id,
                    'name' => $proficiency->name,
                    'origin' => $origin
                ];
            }
        }
        return $character;
    }
}
