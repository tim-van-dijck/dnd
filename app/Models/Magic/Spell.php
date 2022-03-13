<?php

namespace App\Models\Magic;

use App\Models\Character\CharacterClass;
use App\Models\Character\Race;
use App\Models\Character\Subclass;
use App\Models\Character\Subrace;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Class Spell
 * @package App\Models\Magic
 * @property int id
 * @property string name
 * @property int range
 * @property string components
 * @property string materials
 * @property bool ritual
 * @property bool concentration
 * @property string duration
 * @property string casting_time
 * @property int level
 * @property string school
 * @property string description
 * @property string higher_levels
 *
 * @property Collection|CharacterClass[] classes
 * @property Collection|Subclass[] subclasses
 */
class Spell extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'range',
        'components',
        'materials',
        'ritual',
        'concentration',
        'duration',
        'casting_time',
        'level',
        'school',
        'description',
        'higher_levels'
    ];

    /**
     * @return MorphToMany
     */
    public function classes()
    {
        return $this->morphedByMany(CharacterClass::class, 'entity', 'spell_morph', 'spell_id', 'entity_id')
            ->withPivot('optional');
    }

    /**
     * @return MorphToMany
     */
    public function subclasses()
    {
        return $this->morphedByMany(Subclass::class, 'entity', 'spell_morph', 'spell_id', 'entity_id')
            ->withPivot('optional');
    }

    /**
     * @return MorphToMany
     */
    public function races()
    {
        return $this->morphToMany(Race::class, 'entity', 'spell_morph', 'spell_id', 'entity_id')
            ->withPivot('optional');
    }

    /**
     * @return MorphToMany
     */
    public function subraces()
    {
        return $this->morphToMany(Subrace::class, 'entity', 'spell_morph', 'spell_id', 'entity_id')
            ->withPivot('optional');
    }
}
