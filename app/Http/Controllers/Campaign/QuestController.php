<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestResource;
use App\Models\Campaign\Quest;
use App\Models\Campaign\QuestObjective;
use App\Repositories\QuestRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Session;

class QuestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param QuestRepository $questRepository
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(QuestRepository $questRepository, Request $request)
    {
        $page = $request->query('page', []);
        $campaignId = Session::get('campaign_id');
        $filters = $request->query('filters', []);
        $quests = $questRepository->get($campaignId, $filters, $page['number'] ?? 1, $page['size'] ?? 20);
        return QuestResource::collection($quests);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param QuestRepository $questRepository
     * @param Request $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(QuestRepository $questRepository, Request $request)
    {
        $this->authorize('create', Quest::class);
        $this->validate($request, [
            'title' => 'required|string|max:191',
            'description' => 'string',
            'location_id' => 'int|in:locations,id',
            'objectives' => 'required|array|min:1',
            'objectives.*.name' => 'required|string|max:191',
            'objectives.*.optional' => 'required|boolean',
            'permissions' => 'sometimes|nullable|array',
            'permissions.*.view' => 'required|boolean',
            'permissions.*.create' => 'required|boolean',
            'permissions.*.edit' => 'required|boolean',
            'permissions.*.delete' => 'required|boolean',
        ]);
        $questRepository->store(Session::get('campaign_id'), $request->input());
    }

    /**
     * Display the specified resource.
     *
     * @param Quest $quest
     * @return Quest
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Quest $quest): Quest
    {
        $this->authorize('view', $quest);
        $quest->load('objectives');
        if (Session::get('campaign_id') != $quest->campaign_id) {
            throw new ModelNotFoundException();
        }
        return $quest;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param QuestRepository $questRepository
     * @param Request $request
     * @param Quest $quest
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(QuestRepository $questRepository, Request $request, Quest $quest)
    {
        $this->authorize('update', $quest);
        $this->validate($request, [
            'title' => 'required|string|max:191',
            'description' => 'string',
            'location_id' => 'int|in:locations,id',
            'objectives' => 'required|array|min:1',
            'objectives.*.name' => 'required|string|max:191',
            'objectives.*.optional' => 'required|boolean',
            'permissions' => 'sometimes|nullable|array',
            'permissions.*.view' => 'required|boolean',
            'permissions.*.create' => 'required|boolean',
            'permissions.*.edit' => 'required|boolean',
            'permissions.*.delete' => 'required|boolean',
        ]);
        $questRepository->update(Session::get('campaign_id'), $quest, $request->input());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param QuestRepository $questRepository
     * @param Quest $quest
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(QuestRepository $questRepository, Quest $quest)
    {
        $this->authorize('destroy', $quest);
        $questRepository->destroy(Session::get('campaign_id'), $quest);
    }

    /**
     * @param Request $request
     * @param int $questId
     * @param int $objectiveId
     * @return QuestObjective
     * @throws \Illuminate\Validation\ValidationException
     */
    public function toggleObjectiveStatus(Request $request, int $questId, int $objectiveId)
    {
        $this->validate($request, [
            'status' => 'required|int|in:0,1,2'
        ]);

        /** @var QuestObjective $objective */
        $objective = QuestObjective::where([
            'quest_objectives.quest_id' => $questId,
            'quest_objectives.id' => $objectiveId
        ])
            ->join('quests', function ($join) {
                $join->on('quests.id', '=', 'quest_objectives.quest_id')
                    ->where('quests.campaign_id', Session::get('campaign_id'));
            })
            ->firstOrFail();
        $objective->status = $request->input('status');
        $objective->save();
        return $objective;
    }
}
