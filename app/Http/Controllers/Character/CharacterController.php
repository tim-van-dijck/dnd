<?php

namespace App\Http\Controllers\Character;

use App\Http\Controllers\Controller;
use App\Http\Resources\CharacterResource;
use App\Models\Character\Character;
use App\Repositories\CharacterRepository;
use Illuminate\Http\Request;
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
        $characters = $characterRepository
            ->get(Session::get('campaign_id'), $filters, $page['number'] ?? null, $page['size'] ?? null);
        return CharacterResource::collection($characters);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CharacterRepository $characterRepository
     * @param  \Illuminate\Http\Request $request
     * @param int $campaignId
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(CharacterRepository $characterRepository, Request $request)
    {
        $this->authorize('create', Character::class);
        $this->validate($request, [
            'name' => 'required|string',
            'title' => 'string',
            'race_id' => 'required_if:type,player|integer',
            'type' => 'required|string',
            'age' => 'integer',
            'dead' => 'boolean',
            'private' => 'boolean',
            'bio' => 'string',
        ]);
        $characterRepository->store(Session::get('campaign_id'), $request->input());
    }

    /**
     * Display the specified resource.
     *
     * @param CharacterRepository $characterRepository
     * @param int $characterId
     * @return Character
     */
    public function show(CharacterRepository $characterRepository, int $characterId): Character
    {
        $this->authorize('view', [Character::class, $characterId]);
        return $characterRepository->find(Session::get('campaign_id'), $characterId);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $characterId
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, int $characterId)
    {
        $this->authorize('update', [Character::class, $characterId]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CharacterRepository $characterRepository
     * @param int $characterId
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(CharacterRepository $characterRepository, int $characterId)
    {
        $this->authorize('delete', [Character::class, $characterId]);
        $characterRepository->destroy(Session::get('campaign_id'), $characterId);
    }
}
