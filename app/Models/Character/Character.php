<?php

namespace App\Models\Character;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Character
 * @package App\Models\Character
 * @property int id
 * @property int campaign_id
 * @property int race_id
 * @property int|null subrace_id
 * @property string name
 * @property string title
 * @property string type
 * @property string age
 * @property string alignment
 * @property bool dead
 * @property bool private
 * @property string bio
 * @property string ability_scores
 * @property string trait
 * @property string ideal
 * @property string bond
 * @property string flaw
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property Race race
 * @property Subrace subrace
 * @property Collection|CharacterClass[] classes
 * @property Collection|Subclass[] subclasses
 * @property Collection|Proficiency[] proficiencies
 * @property Collection|Language[] languages
 */
class Character extends Model
{

    protected $fillable = [
        'name', 'title', 'type', 'age', 'alignment', 'dead', 'bio', 'ability_scores', 'trait', 'ideal', 'bond', 'flaw'
    ];
    protected $casts = ['ability_scores' => 'array'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function race()
    {
        return $this->belongsTo(Race::class);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subrace()
    {
        return $this->belongsTo(Subrace::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function classes()
    {
        return $this->belongsToMany(CharacterClass::class, 'character_class', 'character_id', 'class_id')
            ->withPivot(['level', 'subclass_id']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subclasses()
    {
        return $this->belongsToMany(Subclass::class, 'character_class', 'character_id', 'class_id')
            ->withPivot(['level']);
    }

    public function proficiencies()
    {
        return $this->belongsToMany(Proficiency::class)->withPivot(['origin_type', 'origin_id']);
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class);
    }
}
