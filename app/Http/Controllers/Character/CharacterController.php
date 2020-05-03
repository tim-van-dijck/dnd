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
     * @param  \App\Models\Character\Character  $character
     * @return \Illuminate\Http\Response
     */
    public function show(Character $character)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Character\Character  $character
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Character $character)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Character\Character  $character
     * @return \Illuminate\Http\Response
     */
    public function destroy(Character $character)
    {
        //
    }
}
