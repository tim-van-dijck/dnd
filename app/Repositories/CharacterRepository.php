<?php

namespace App\Repositories;

use App\Enums\CharacterTypes;
use App\Models\Campaign\Quest;
use App\Models\Character\Character;
use App\Services\AuthService;
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
    public function get(int $campaignId, array $filters, array $includes, int $page = 1, int $pageSize = 20)
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
        if (!empty($includes)) {
            foreach ($includes as $include) {
                $query->with($include);
            }
            $query->with($includes);
        }
        return $query->paginate($pageSize, ['characters.*'], 'page[number]', $page);
    }

    /**
     * @param int $campaignId
     * @param array $input
     *
     * @return Character
     */
    public function store(int $campaignId, array $input): Character
    {
        $character = new Character($input);
        $character->campaign_id = $campaignId;
        $character->race_id = $input['race_id'];
        if (!empty($input['subrace_id'])) {
            $character->subrace_id = $input['subrace_id'];
        }
        $character->private = !empty($input['private']);
        $character->save();

        if ($character->private) {
            AuthService::setPrivateEntity($campaignId, 'character', $character->id, Auth::user()->id);
        }

        $this->logRepository->store($campaignId, 'character', $character->id, $character->name, 'created');
        return $character;
    }

    /**
     * @param int $campaignId
     * @param int $characterId
     * @return Character
     */
    public function find(int $campaignId, int $characterId, array $includes = []): Character
    {
        return Character::where(['campaign_id' => $campaignId, 'id' => $characterId])->with($includes)->firstOrFail();
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
        $character = Character::where(['campaign_id' => $campaignId, 'id' => $characterId])->firstOrFail();
        $character->languages()->sync([]);
        $character->proficiencies()->sync([]);
        $character->classes()->sync([]);
        $character->spells()->sync([]);
        $character->delete();
        $this->logRepository->store($campaignId, 'character', $character->id, $character->name, 'deleted');
    }
}