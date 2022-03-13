<?php

namespace App\Http\Controllers\Magic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SpellRequest;
use App\Http\Resources\SpellResource;
use App\Models\Magic\Spell;
use App\Repositories\Magic\SpellRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpellController extends Controller
{
    public function index(SpellRepository $spellRepository, Request $request)
    {
        $page = $request->query('page');
        $spells = $spellRepository
            ->get($request->query('filters', []), $page['number'] ?? 1, $page['size'] ?? 20);
        return SpellResource::collection($spells);
    }

    public function store(SpellRepository $spellRepository, SpellRequest $request): Spell
    {
        return $spellRepository->store($request->input());
    }

    public function show(Spell $spell): Spell
    {
        return $spell;
    }

    public function update(SpellRepository $spellRepository, SpellRequest $request, Spell $spell): Spell
    {
        return $spellRepository->update($spell, $request->input());
    }

    public function destroy(SpellRepository $spellRepository, Spell $spell)
    {
        $spellRepository->destroy($spell);
    }

    public function all(): JsonResponse
    {
        return response()->json(Spell::get());
    }
}
