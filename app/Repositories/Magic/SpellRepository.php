<?php

namespace App\Repositories\Magic;

use App\Models\Magic\Spell;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class SpellRepository
{
    /**
     * @param array $filters
     * @param int $page
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function get(array $filters, int $page = 1, int $pageSize = 20): LengthAwarePaginator
    {
        $query = Spell::query();

        if (!empty($filters['query'])) {
            $query->where('name', 'LIKE', "%{$filters['query']}%");
        }

        return $query->orderBy('name')->paginate($pageSize, ['*'], 'page[number]', $page);
    }

    /**
     * @param int $spellId
     * @return Spell
     * @throws ModelNotFoundException
     */
    public function find(int $spellId): Spell
    {
        return Spell::findOrFail($spellId);
    }

    /**
     * @param array $input
     * @return Spell
     */
    public function store(array $input): Spell
    {
        $spell = new Spell($input);
        $spell->components = implode(',', $input['components']);
        $spell->save();
        return $spell;
    }

    /**
     * @param Spell $spell
     * @param array $input
     * @return Spell
     */
    public function update(Spell $spell, array $input): Spell
    {
        $spell->fill($input);
        $spell->components = implode(',', $input['components']);
        $spell->save();
        return $spell;
    }

    public function destroy(Spell $spell)
    {
        DB::table('spell_morph')->where('spell_id', $spell->id)->delete();
        DB::table('character_spell')->where('spell_id', $spell->id)->delete();
        $spell->delete();
    }
}
