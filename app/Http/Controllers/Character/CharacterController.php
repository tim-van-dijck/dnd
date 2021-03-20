<?php

namespace App\Http\Controllers\Character;

use App\Http\Controllers\Controller;
use App\Http\Requests\CharacterRequest;
use App\Http\Resources\CharacterResource;
use App\Managers\CharacterManager;
use App\Models\Character\Character;
use App\Repositories\Character\CharacterRepository;
use App\Services\Character\CharacterSheetBuilder;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;

class CharacterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param CharacterRepository $characterRepository
     * @param Request $request
     * @param int $campaignId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(CharacterRepository $characterRepository, Request $request)
    {
        $this->authorize('viewAny', Character::class);
        $filters = $request->query('filter', []);
        $page = $request->query('page', []);
        $includes = $request->has('includes') ? explode(',', $request->query('includes')) : [];
        $characters = $characterRepository
            ->get(Session::get('campaign_id'), $filters, $includes, $page['number'] ?? 1, $page['size'] ?? 20);
        return CharacterResource::collection($characters);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CharacterManager $characterManager
     * @param  CharacterRequest $request
     * @param int $campaignId
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(CharacterManager $characterManager, CharacterRequest $request): CharacterResource
    {
        $this->authorize('create', Character::class);
        $request->validate($request->rules());
        $character = $characterManager->store(Session::get('campaign_id'), $request->input());
        return new CharacterResource($character);
    }

    /**
     * Display the specified resource.
     *
     * @param CharacterRepository $characterRepository
     * @param Request $request
     * @param Character $character
     * @return CharacterResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(CharacterRepository $characterRepository, Request $request, Character $character): CharacterResource
    {
        $this->authorize('view', $character);
        $includes = $request->has('includes') ? explode(',', $request->query('includes')) : [];
        return new CharacterResource($characterRepository->find(Session::get('campaign_id'), $character->id, $includes));
    }

    /**
     * Export the specified character as a PDF Character Sheet.
     *
     * @param CharacterRepository $characterRepository
     * @param Character $character
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function sheet(CharacterSheetBuilder $builder, Character $character): Response
    {
        $this->authorize('view', $character);
        return $builder->getSheet($character);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CharacterManager $characterManager
     * @param CharacterRequest $request
     * @param Character $character
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CharacterManager $characterManager, CharacterRequest $request, Character $character): CharacterResource
    {
        $this->authorize('update', $character);
        $request->validate($request->rules());
        $character = $characterManager->update(Session::get('campaign_id'), $character->id, $character->type, $request->input());
        return new CharacterResource($character);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CharacterRepository $characterRepository
     * @param Character $character
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(CharacterRepository $characterRepository, Character $character)
    {
        $this->authorize('delete', $character);
        $characterRepository->destroy(Session::get('campaign_id'), $character->id);
    }
}
