<?php

namespace App\Repositories\Character;

use App\Enums\CharacterTypes;
use App\Models\Campaign\Quest;
use App\Models\Character\Character;
use App\Repositories\LogRepository;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CharacterRepository
{
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
                        'user_permissions.entity' => 'character',
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
        $character->background_id = $input['background_id'];
        $character->owner_id = $this->verifiedOwnerId($input['owner_id'] ?? null) ?? Auth::user()->id;
        $character->private = !empty($input['private']);
        $character->save();

        AuthService::managePermissions(
            $campaignId,
            'character',
            $character->id,
            $data['permissions'] ?? [],
            $character->private
        );
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

    /**
     * @param int $campaignId
     * @param int $characterId
     * @param array $input
     * @return Character
     * @throws ModelNotFoundException
     */
    public function update(int $campaignId, int $characterId, array $input): Character
    {
        $character = $this->find($campaignId, $characterId);
        $character->fill($input);

        $character->race_id = $input['race_id'];
        if (!empty($input['subrace_id'])) {
            $character->subrace_id = $input['subrace_id'];
        }
        $character->background_id = $input['background_id'];
        $character->owner_id = $this->verifiedOwnerId($input['owner_id'], $character->owner_id) ?? $character->owner_id;
        $character->private = !empty($input['private']);
        $character->save();

        AuthService::managePermissions(
            $campaignId,
            'character',
            $character->id,
            $data['permissions'] ?? [],
            $character->private
        );
        return $character;
    }

    /**
     * @param int $campaignId
     * @param int $characterId
     * @throws \Exception
     */
    public function destroy(int $campaignId, int $characterId)
    {
        /** @var Character $character */
        $character = $this->find($campaignId, $characterId);
        $character->languages()->sync([]);
        $character->proficiencies()->sync([]);
        $character->classes()->sync([]);
        $character->spells()->sync([]);
        $character->features()->sync([]);
        $character->delete();

        /** @var LogRepository $logRepository */
        $logRepository = resolve(LogRepository::class);
        $logRepository->store($campaignId, 'character', $character->id, $character->name, 'deleted');
    }

    public function verifiedOwnerId(?int $ownerId, ?int $currentOwnerId = null): ?int
    {
        if ($ownerId === null) {
            return null;
        }

        if (Auth::user()->roles()->where('name', 'Admin')->exists() || $currentOwnerId === Auth::user()->id) {
            $userRepository = new UserRepository();
            return $userRepository->userExistsInCampaign(Session::get('campaign_id'), $ownerId) ? $ownerId : null;
        }
        return null;
    }
}