<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CampaignResource;
use App\Managers\CampaignManager;
use App\Models\Campaign\Campaign;
use App\Repositories\CampaignRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CampaignController extends Controller
{
    public function index(CampaignRepository $campaignRepository, Request $request)
    {
        $page = $request->query('page', [])['number'] ?? null;
        $pageSize = $request->query('page', [])['size'] ?? null;
        $filters = $request->query('filters', []);
        if (empty($filters['user_id'])) {
            $campaigns = $campaignRepository->get($request->query('filters', []), $page, $pageSize);
        } else {
            $campaigns = $campaignRepository->getByUserId($filters['user_id']);
        }
        return CampaignResource::collection($campaigns);
    }

    /**
     * @param Request $request
     * @param CampaignManager $campaignManager
     * @return Campaign
     * @throws ValidationException
     */
    public function store(Request $request, CampaignManager $campaignManager): Campaign
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'description' => 'string|nullable'
        ]);

        return $campaignManager->create($request->input());
    }

    public function show(Campaign $campaign): Campaign
    {
        return $campaign;
    }

    /**
     * @param Request $request
     * @param CampaignRepository $campaignRepository
     * @param Campaign $campaign
     * @return Campaign
     * @throws ValidationException
     */
    public function update(Request $request, CampaignRepository $campaignRepository, Campaign $campaign): Campaign
    {
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'string'
        ]);
        return $campaignRepository->update($campaign, $request->input());
    }

    /**
     * @param CampaignRepository $campaignRepository
     * @param Campaign $campaign
     */
    public function destroy(CampaignRepository $campaignRepository, Campaign $campaign)
    {
        $campaignRepository->destroy($campaign);
    }
}
