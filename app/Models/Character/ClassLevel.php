<?php

namespace App\Models\Character;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ClassLevel
 * @package App\Models\Character
 * @property int id
 * @property int class_id
 * @property int level
 * @property int cantrips_known
 * @property int spells_known
 * @property int spell_slots_level_1
 * @property int spell_slots_level_2
 * @property int spell_slots_level_3
 * @property int spell_slots_level_4
 * @property int spell_slots_level_5
 * @property int spell_slots_level_6
 * @property int spell_slots_level_7
 * @property int spell_slots_level_8
 * @property int spell_slots_level_9
 * @property array class_specific
 *
 * @property CharacterClass class
 */
class ClassLevel extends Model
{
    public $timestamps = false;

    protected $casts = [
        'class_specific' => 'array'
    ];

    /**
     * @return BelongsTo
     */
    public function class()
    {
        return $this->belongsTo(CharacterClass::class, 'class_id');
    }
}
