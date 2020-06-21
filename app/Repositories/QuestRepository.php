<?php

namespace App\Repositories;

use App\Models\Campaign\Quest;
use App\Models\Campaign\QuestObjective;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class QuestRepository
{
    /**
     * QuestRepository constructor.
     */
    public function __construct()
    {
        $this->logRepository = app(LogRepository::class);
    }

    /** @var LogRepository */
    private $logRepository;

    /**
     * @param int $campaignId
     * @param array $filters
     * @param int $page
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function get(int $campaignId, array $filters = [], int $page = 1, int $pageSize = 20): LengthAwarePaginator
    {
        $query = Quest::query()
            ->where('quests.campaign_id', $campaignId)
            ->leftJoin('user_permissions', function ($join) {
                $join->on('quests.id', '=', 'user_permissions.entity_id')
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
        $query->with('objectives');

        if (array_key_exists('completed', $filters)) {
            $query->whereDoesntHave('objectives', function ($query) use ($filters) {
                return $query->whereIn('status', [QuestObjective::STATUS_FAILED, QuestObjective::STATUS_OPEN]);
            });
        }

        if (array_key_exists('query', $filters)) {
            $query->where(function ($query) use ($filters) {
                return $query->where('title', 'LIKE', "%{$filters['query']}%")
                    ->orWhere('description', 'LIKE', "%{$filters['query']}%");
            });
        }

        return $query->paginate($pageSize, ['quests.*'], 'page[number]', $page);
    }

    /**
     * @param int $campaignId
     * @param array $data
     */
    public function store(int $campaignId, array $data)
    {
        $quest = new Quest();
        $quest->campaign_id = $campaignId;
        $quest->location_id = $data['location_id'] ?? null;
        $quest->title = $data['title'];
        $quest->description = $data['description'];
        $quest->save();

        foreach ($data['objectives'] as $objective) {
            $questObjective = new QuestObjective();
            $questObjective->quest_id = $quest->id;
            $questObjective->name = $objective['name'];
            $questObjective->optional = $objective['optional'];
            $questObjective->status = 0;
            $questObjective->save();
        }

        $this->logRepository->store($campaignId, 'quest', $quest->id, $quest->title, 'created');
    }

    /**
     * @param int $campaignId
     * @param Quest $quest
     * @param array $data
     */
    public function update(int $campaignId, Quest $quest, array $data)
    {
        if ($campaignId != $quest->campaign_id) {
            throw new ModelNotFoundException();
        }
        $quest->location_id = $data['location_id'] ?? null;
        $quest->title = $data['title'];
        $quest->description = $data['description'];
        $quest->save();

        foreach ($data['objectives'] as $objective) {
            if (array_key_exists('id', $objective)) {
                $questObjective = QuestObjective::where(['quest_id' => $quest->id, 'id' => $objective['id']])
                    ->firstOrFail();
            } else {
                $questObjective = new QuestObjective();
                $questObjective->quest_id = $quest->id;
                $questObjective->status = 0;
            }
            $questObjective->name = $objective['name'];
            $questObjective->optional = $objective['optional'];
            $questObjective->save();
        }

        $this->logRepository->store($campaignId, 'location', $quest->id, $quest->title, 'updated');
    }

    /**
     * @param int $campaignId
     * @param Quest $quest
     * @throws \Exception
     */
    public function destroy(int $campaignId, Quest $quest)
    {
        if ($campaignId != $quest->campaign_id) {
            throw new ModelNotFoundException();
        }
        $quest->objectives()->delete();
        $quest->delete();

        $this->logRepository->store($campaignId, 'location', $quest->id, $quest->title, 'deleted');
    }
}