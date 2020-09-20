<?php

namespace App\Models\Magic;

use App\Models\Character\CharacterClass;
use App\Models\Character\Subclass;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Spell
 * @package App\Models\Magic
 * @property int id
 * @property string name
 * @property int range
 * @property string components
 * @property bool ritual
 * @property bool concentration
 * @property string duration
 * @property string casting_time
 * @property int level
 * @property string school
 *
 * @property Collection|CharacterClass[] classes
 * @property Collection|Subclass[] subclasses
 */
class Spell extends Model
{
    public $timestamps = false;

    /**
     * @return BelongsToMany
     */
    public function classes()
    {
        return $this->belongsToMany(CharacterClass::class, 'class_spell', 'spell_id', 'class_id');
    }

    /**
     * @return BelongsToMany
     */
    public function subclasses()
    {
        return $this->belongsToMany(Subclass::class, 'class_spell');
    }
}
