<?php

namespace App\Http\Resources;

use App\Enums\OriginTypes;
use App\Models\Character\Character;
use App\Models\Character\Proficiency;
use App\Models\Equipment\Inventory;
use App\Models\Magic\Spell;
use App\Services\Character\Helpers\CharacterFeatureHelper;
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
        $includes = explode(',', $request->query('includes', ''));
        $inventory = Inventory::query()->where('character_id', $this->resource->id)->first(['id']);
        $character = [
            'id' => $this->resource->id,
            'info' => [
                'name' => $this->resource->name,
                'title' => $this->resource->title ?? '',
                'type' => $this->resource->type,
                'age' => $this->resource->age,
                'alignment' => $this->resource->alignment,
                'dead' => $this->resource->dead ?? false,
                'private' => $this->resource->private ?? false,
                'bio' => $this->resource->bio ?? '',
                'owner_id' => $this->resource->owner_id,
                'inventory_id' => $inventory ? $inventory->id : null
            ],
            'background_id' => $this->resource->background_id ?? null,
            'ability_scores' => $this->resource->ability_scores,
            'owner' => $this->getOwner()
        ];

        if ($this->resource->type == 'player') {
            $character['personality'] = [
                'trait' => $this->resource->trait,
                'ideal' => $this->resource->ideal,
                'bond' => $this->resource->bond,
                'flaw' => $this->resource->flaw
            ];
        }

        if (in_array('classes', $includes)) {
            $character['classes'] = $this->getClasses();
        }
        if (in_array('race', $includes)) {
            $character['race'] = $this->getRace();
        }
        if (in_array('proficiencies', $includes)) {
            $character['proficiencies'] = $this->getProficiencies();
        }
        if (in_array('languages', $includes)) {
            if (!array_key_exists('proficiencies', $character)) {
                $character['proficiencies'] = [];
            }
            $character['proficiencies']['languages'] = $this->resource->languages->pluck('id')->toArray();
        }
        if (in_array('spells', $includes)) {
            $character['spells'] = $this->getSpells();
        }

        return $character;
    }

    public function getRace(): array
    {
        $race = [
            'id' => $this->resource->race->id,
            'name' => $this->resource->race->name,
            'speed' => $this->resource->race->speed,
            'size' => $this->resource->race->size,
        ];
        if ($this->resource->subrace) {
            $race['subrace'] = [
                'id' => $this->resource->subrace->id,
                'name' => $this->resource->subrace->name,
                'description' => $this->resource->subrace->description
            ];
        }
        return $race;
    }

    public function getClasses(): array
    {
        $classes = [];
        foreach ($this->resource->classes as $charClass) {
            $features = CharacterFeatureHelper::getCharacterClassFeatures(
                $this->resource->id,
                $charClass->id,
                $charClass->pivot->level,
                $charClass->pivot->subclass_id
            );

            $classes[] = [
                'id' => $charClass->id,
                'name' => $charClass->name,
                'subclass' => $charClass->subclasses->find($charClass->pivot->subclass_id),
                'level' => $charClass->pivot->level,
                'features' => $features
            ];
        }
        return $classes;
    }

    public function getSpells(): array
    {
        $spells = [
            'cantrips' => [],
            'spells' => []
        ];
        /** @var Spell $spell */
        foreach ($this->resource->spells as $spell) {
            $spellArray = [
                'id' => $spell->id,
                'name' => $spell->name,
                'level' => $spell->level,
                'school' => $spell->school,
                'origin_id' => $spell->pivot->origin_id,
                'origin_type' => OriginTypes::getOrigin($spell->pivot->origin_type)
            ];
            if ($spell->level > 0) {
                $spells['spells'][] = $spellArray;
            } else {
                $spells['cantrips'][] = $spellArray;
            }
        }
        return $spells;
    }

    public function getProficiencies(): array
    {
        $proficiencies = [];
        /** @var Proficiency $proficiency */
        foreach ($this->resource->proficiencies as $proficiency) {
            $proficiencies[strtolower($proficiency->type)][] = [
                'id' => $proficiency->id,
                'name' => $proficiency->name,
                'origin_type' => OriginTypes::getOrigin($proficiency->pivot->origin_type),
                'origin_id' => $proficiency->pivot->origin_id
            ];
        }
        return $proficiencies;
    }

    private function getOwner()
    {
        if (empty($this->resource->owner_id)) {
            return null;
        }

        return $this->resource->owner->name;
    }
}
