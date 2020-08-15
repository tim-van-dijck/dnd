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
 * @property int|null player_id
 * @property int|null race_id
 * @property string name
 * @property string title
 * @property string type
 * @property string age
 * @property bool dead
 * @property bool private
 * @property string bio
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property Race race
 * @property Collection|CharacterClass[] classes
 * @property Collection|Subclass[] subclasses
 */
class Character extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function race()
    {
        return $this->belongsTo(Race::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function classes()
    {
        return $this->belongsToMany(CharacterClass::class, null, 'character_id', 'class_id')
            ->withPivot(['level']);
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
}
