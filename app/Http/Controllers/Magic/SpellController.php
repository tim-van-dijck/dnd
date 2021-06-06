<?php

namespace App\Http\Controllers\Magic;

use App\Http\Controllers\Controller;
use App\Http\Resources\SpellResource;
use App\Models\Magic\Spell;
use App\Repositories\Magic\SpellRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpellController extends Controller
{
    public function index(SpellRepository $spellRepository, Request $request)
    {
        $spells = $spellRepository
            ->get($request->query('filters', []), $page['number'] ?? 1, $page['size'] ?? 20);
        return SpellResource::collection($spells);
    }

    public function store(SpellRepository $spellRepository, Request $request): Spell
    {
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'required|string',
            'higher_levels' => 'nullable|string',
            'level' => 'required|integer|between:0,9',
            'ritual' => 'boolean',
            'concentration' => 'boolean',
            'components' => 'required|array',
            'components.*' => 'required|string|in:V,S,M',
        ]);

        return $spellRepository->store($request->input());
    }

    public function show(Spell $spell): Spell
    {
        return $spell;
    }

    public function update(SpellRepository $spellRepository, Request $request, Spell $spell): Spell
    {
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'required|string',
            'higher_levels' => 'nullable|string',
            'level' => 'required|integer|between:0,9',
            'ritual' => 'boolean',
            'concentration' => 'boolean',
            'components' => 'required|array',
            'components.*' => 'required|string|in:V,S,M',
        ]);

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
