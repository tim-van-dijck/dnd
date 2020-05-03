<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Managers\CampaignManager;
use App\Models\Campaign\Campaign;
use App\Repositories\CampaignRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CampaignController extends Controller
{
    /**
     * @param CampaignRepository $campaignRepository
     * @return \Illuminate\View\View
     */
    public function index(CampaignRepository $campaignRepository)
    {
        $campaigns = $campaignRepository->index(Auth::user()->id);
        return view('campaigns.index', ['campaigns' => $campaigns]);
    }

    public function create()
    {
        return view('campaigns.create');
    }

    /**
     * @param Request $request
     * @param CampaignManager $campaignManager
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, CampaignManager $campaignManager)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'description' => 'string'
        ]);

        $campaign = $campaignManager->create($request->input());
        Session::put('campaign_id', $campaign->id);
        return redirect('/');
    }

    /**
     * @param Campaign $campaign
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Campaign $campaign)
    {
        if (Auth::user()->can('view', $campaign)) {
            Session::put('campaign_id', $campaign->id);
        }
        return redirect('/');
    }

    /**
     * @param Campaign $campaign
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Campaign $campaign)
    {
        return view('campaigns.edit', ['campaign' => $campaign]);
    }

    /**
     * @param Request $request
     * @param CampaignRepository $campaignRepository
     * @param Campaign $campaign
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, CampaignRepository $campaignRepository, Campaign $campaign)
    {
        $this->validate($request, [
           'name' => 'required|string',
           'description' => 'string'
        ]);
        $campaignRepository->update($campaign, $request->input());
        return redirect()->route('campaigns.index');
    }

    /**
     * @param CampaignManager $campaignManager
     * @param Campaign $campaign
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(CampaignManager $campaignManager, Campaign $campaign)
    {
        $campaignManager->destroy($campaign);
        return redirect()->route('campaigns.index');
    }
}