<?php

namespace App\Repositories;

use App\Enums\CharacterTypes;
use App\Models\Character\Character;

class CharacterRepository
{
    public function get(int $campaignId, array $filters, $page = 1, $pageSize = 20)
    {
        $type = $filters['type'] == CharacterTypes::PLAYER ? CharacterTypes::PLAYER : CharacterTypes::NPC;
        return Character::query()->where([
            'campaign_id' => $campaignId, 'type' => $type
        ])
            ->paginate($pageSize, ['*'], 'page[number]', $page);
    }

    public function store(int $campaignId, array $input)
    {
        $character = new Character();
        $character->campaign_id = $campaignId;
        $character->race_id = $input['race_id'];
        $character->name = $input['name'];
        $character->type = $input['type'] == CharacterTypes::PLAYER ? CharacterTypes::PLAYER : $input['type'];
        $character->title = $input['title'];
        $character->age = $input['age'];
        $character->dead = !empty($input['dead']);
        $character->bio = $input['bio'];
        $character->save();
    }
}