<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestResource;
use App\Models\Campaign\Quest;
use App\Models\Campaign\QuestObjective;
use App\Repositories\QuestRepository;
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
        $page = $request->query('page');
        $quests = $questRepository
            ->get(Session::get('campaign_id'), $request->query('filters', []), $page['number'] ?? 1, $page['size'] ?? 20);
        return QuestResource::collection($quests);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param QuestRepository $questRepository
     * @param  Request $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(QuestRepository $questRepository, Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:191',
            'description' => 'string',
            'location_id' => 'int|in:locations,id',
            'objectives' => 'required|array|min:1',
            'objectives.*.name' => 'required|string|max:191',
            'objectives.*.optional' => 'required|boolean'
        ]);
        $questRepository->store(Session::get('campaign_id'), $request->input());
    }

    /**
     * Display the specified resource.
     *
     * @param QuestRepository $questRepository
     * @param int $questId
     * @return Quest
     */
    public function show(QuestRepository $questRepository, int $questId)
    {
        return $questRepository->find(Session::get('campaign_id'), $questId);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param QuestRepository $questRepository
     * @param Request $request
     * @param int $questId
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(QuestRepository $questRepository, Request $request, int $questId)
    {
        $this->validate($request, [
            'title' => 'required|string|max:191',
            'description' => 'string',
            'location_id' => 'int|in:locations,id',
            'objectives' => 'required|array|min:1',
            'objectives.*.name' => 'required|string|max:191',
            'objectives.*.optional' => 'required|boolean'
        ]);
        $questRepository->update(Session::get('campaign_id'), $questId, $request->input());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param QuestRepository $questRepository
     * @param int $questId
     * @return void
     */
    public function destroy(QuestRepository $questRepository, int $questId)
    {
        $questRepository->destroy(Session::get('campaign_id'), $questId);
    }

    public function toggleObjectiveStatus(Request $request, int $questId, int $objectiveId)
    {
        $this->validate($request, [
            'status' => 'required|int|in:0,1,2'
        ]);

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
