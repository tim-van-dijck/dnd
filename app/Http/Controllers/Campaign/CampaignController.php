<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Http\Resources\LogResource;
use App\Managers\CampaignManager;
use App\Models\Campaign\Campaign;
use App\Repositories\CampaignRepository;
use App\Repositories\LogRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CampaignController extends Controller
{
    /**
     * @param CampaignRepository $campaignRepository
     * @return View
     */
    public function index(CampaignRepository $campaignRepository): View
    {
        $campaigns = $campaignRepository->getByUserId(Auth::user()->id);
        return view('campaigns.index', ['campaigns' => $campaigns]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('campaigns.create');
    }

    /**
     * @param Request $request
     * @param CampaignManager $campaignManager
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request, CampaignManager $campaignManager): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'description' => 'string|nullable'
        ]);

        $campaign = $campaignManager->create($request->input());
        Session::put('campaign_id', $campaign->id);
        return redirect('/');
    }

    /**
     * @param Campaign $campaign
     * @return RedirectResponse
     */
    public function show(Campaign $campaign): RedirectResponse
    {
        if (Auth::user()->can('view', $campaign)) {
            Session::put('campaign_id', $campaign->id);
        }
        return redirect('/');
    }

    public function currentCampaign(): JsonResponse
    {
        /** @var Campaign $campaign */
        $campaign = Campaign::findOrFail(Session::get('campaign_id'));
        if (Auth::user()->can('view', $campaign)) {
            return response()->json([
                'name' => $campaign->name,
                'description' => $campaign->description,
            ]);
        } else {
            abort(403);
        }
    }

    public function edit(Campaign $campaign): View
    {
        return view('campaigns.edit', ['campaign' => $campaign]);
    }

    public function update(Request $request, CampaignRepository $campaignRepository, Campaign $campaign): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'string'
        ]);
        $campaignRepository->update($campaign, $request->input());
        return redirect()->route('campaigns.index');
    }

    public function destroy(CampaignRepository $campaignRepository, Campaign $campaign): RedirectResponse
    {
        $campaignRepository->destroy($campaign);
        return redirect()->route('campaigns.index');
    }

    public function logs(LogRepository $logRepository)
    {
        $campaignId = Session::get('campaign_id');
        return LogResource::collection($logRepository->recentActivity($campaignId));
    }

    public function search(Request $request)
    {
        $queryString = $request->query('query', '');

        $chars = DB::table('characters')
            ->selectRaw('characters.id AS id, characters.name AS label, type AS type')
            ->where('campaign_id', Session::get('campaign_id'))
            ->where('characters.name', 'LIKE', "%$queryString%");

        $locations = DB::table('locations')
            ->selectRaw('locations.id AS id, locations.name AS label, "location" AS type')
            ->where('campaign_id', Session::get('campaign_id'))
            ->where('locations.name', 'LIKE', "%$queryString%");

        $notes = DB::table('notes')
            ->selectRaw('notes.id AS id, notes.name AS label, "note" AS type')
            ->where('campaign_id', Session::get('campaign_id'))
            ->where(function ($query) use ($queryString) {
                $query->where('notes.name', 'LIKE', "%$queryString%")
                    ->orWhere('notes.content', 'LIKE', "%$queryString%");
            });

        $quests = DB::table('quests')
            ->selectRaw('quests.id AS id, quests.title AS label, "quest" AS type')
            ->where('campaign_id', Session::get('campaign_id'))
            ->where(function ($query) use ($queryString) {
                $query->where('quests.title', 'LIKE', "%$queryString%")
                    ->orWhere('quests.description', 'LIKE', "%$queryString%");
            });

        return $chars->union($locations)->union($notes)->union($quests)->get();
    }
}