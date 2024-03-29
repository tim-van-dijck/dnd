<?php

namespace App\Http\Controllers\Character;

use App\Http\Controllers\Controller;
use App\Http\Requests\CharacterRequest;
use App\Http\Resources\CharacterResource;
use App\Managers\CharacterManager;
use App\Models\Character\Character;
use App\Repositories\Character\CharacterRepository;
use App\Services\Character\CharacterSheetBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;

class CharacterController extends Controller
{
    public function index(CharacterRepository $characterRepository, Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Character::class);
        $filters = $request->query('filter', []);
        $page = $request->query('page', []);
        $includes = $request->has('includes') ? explode(',', $request->query('includes')) : [];
        $characters = $characterRepository
            ->get(Session::get('campaign_id'), $filters, $includes, $page['number'] ?? 1, $page['size'] ?? 20);
        return CharacterResource::collection($characters);
    }

    public function store(CharacterManager $characterManager, CharacterRequest $request): CharacterResource
    {
        $this->authorize('create', Character::class);
        $request->validate($request->rules());
        $character = $characterManager->store(Session::get('campaign_id'), $request->input());
        return new CharacterResource($character);
    }

    public function show(CharacterRepository $characterRepository, Request $request, Character $character): CharacterResource
    {
        $this->authorize('view', $character);
        $includes = $request->has('includes') ? explode(',', $request->query('includes')) : [];
        return new CharacterResource($characterRepository->find(Session::get('campaign_id'), $character->id, $includes));
    }

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
