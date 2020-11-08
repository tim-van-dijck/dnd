<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestRequest;
use App\Http\Resources\QuestResource;
use App\Models\Campaign\Quest;
use App\Models\Campaign\QuestObjective;
use App\Repositories\QuestRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function store(QuestRepository $questRepository, QuestRequest $request)
    {
        $this->authorize('create', Quest::class);
        $campaignId = Session::get('campaign_id');
        $request->validate($request->rules());
        $questRepository->store($campaignId, $request->input());
    }

    /**
     * Display the specified resource.
     *
     * @param Quest $quest
     * @return Quest
     * @throws AuthorizationException
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
     * @throws AuthorizationException|ValidationException
     */
    public function update(QuestRepository $questRepository, QuestRequest $request, Quest $quest)
    {
        $this->authorize('update', $quest);
        $request->validate($request->rules());
        $questRepository->update(Session::get('campaign_id'), $quest, $request->input());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param QuestRepository $questRepository
     * @param Quest $quest
     * @return void
     * @throws AuthorizationException
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
     * @throws ValidationException
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
            ->firstOrFail(['quest_objectives.*']);
        $objective->status = $request->input('status');
        $objective->save();
        return $objective;
    }
}
