<?php

namespace App\Repositories;

use App\Models\Campaign\Quest;
use App\Models\Campaign\QuestObjective;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
        $query = Quest::query()->where('campaign_id', $campaignId)
            ->with('objectives');

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

        return $query->paginate($pageSize, ['*'], 'page[number]', $page);
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
     * @param int $questId
     * @return Quest
     */
    public function find(int $campaignId, int $questId)
    {
        return Quest::where(['campaign_id' => $campaignId, 'id' => $questId])->with('objectives')->firstOrFail();
    }

    /**
     * @param int $campaignId
     * @param int $questId
     * @param array $data
     */
    public function update(int $campaignId, int $questId, array $data)
    {
        /** @var Quest $quest */
        $quest = Quest::where(['campaign_id' => $campaignId, 'id' => $questId])->firstOrFail();
        $quest->location_id = $data['location_id'] ?? null;
        $quest->title = $data['title'];
        $quest->description = $data['description'];
        $quest->save();

        foreach ($data['objectives'] as $objective) {
            if (array_key_exists('id', $objective)) {
                $questObjective = QuestObjective::where(['quest_id' => $questId, 'id' => $objective['id']])
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
     * @param int $questId
     * @throws \Exception
     */
    public function destroy(int $campaignId, int $questId)
    {
        /** @var Quest $quest */
        $quest = Quest::where(['campaign_id' => $campaignId, 'id' => $questId])->firstOrFail();
        $quest->objectives()->delete();
        $quest->delete();

        $this->logRepository->store($campaignId, 'location', $quest->id, $quest->title, 'deleted');
    }
}