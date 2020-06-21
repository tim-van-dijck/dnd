<?php

namespace App\Repositories;

use App\Enums\CharacterTypes;
use App\Models\Campaign\Quest;
use App\Models\Character\Character;
use Illuminate\Support\Facades\Auth;

class CharacterRepository
{
    /** @var LogRepository */
    private $logRepository;

    /**
     * CharacterRepository constructor.
     */
    public function __construct()
    {
        $this->logRepository = app(LogRepository::class);
    }

    /**
     * @param int $campaignId
     * @param array $filters
     * @param int $page
     * @param int $pageSize
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function get(int $campaignId, array $filters, $page = 1, $pageSize = 20)
    {
        $type = $filters['type'] == CharacterTypes::PLAYER ? CharacterTypes::PLAYER : CharacterTypes::NPC;
        $query = Character::query()->where([
            'characters.campaign_id' => $campaignId,
            'type' => $type
        ])
            ->leftJoin('user_permissions', function ($join) {
                $join->on('characters.id', '=', 'user_permissions.entity_id')
                    ->where([
                        'user_permissions.entity' => 'quest',
                        'user_permissions.user_id' => Auth::user()->id
                    ]);
            });
        if (Auth::user()->can('viewAny', Quest::class)) {
            $query->where(function ($query) {
                $query->where('private', 0)
                    ->orWhere('user_permissions.view', 1);
            });
        } else {
            $query->where('user_permissions.view', 1);
        }
        return $query->paginate($pageSize, ['characters.*'], 'page[number]', $page);
    }

    /**
     * @param int $campaignId
     * @param array $input
     */
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

        $this->logRepository->store($campaignId, 'character', $character->id, $character->name, 'created');
    }

    /**
     * @param int $campaignId
     * @param int $characterId
     * @return Character
     */
    public function find(int $campaignId, int $characterId): Character
    {
        return Character::where(['campaign_id' => $campaignId, 'id' => $characterId])->firstOrFail();
    }

    public function update(int $campaignId, int $characterId, array $data)
    {
//        $this->logRepository->store($campaignId, 'character', $character->id, $character->name, 'updated');
    }

    /**
     * @param int $campaignId
     * @param int $characterId
     * @throws \Exception
     */
    public function destroy(int $campaignId, int $characterId)
    {
        /** @var Character $character */
        $character = Character::where(['campaign_id' => $campaignId, 'id' => $characterId])->findOrFail();
        $character->delete();
        $this->logRepository->store($campaignId, 'character', $character->id, $character->name, 'deleted');
    }
}