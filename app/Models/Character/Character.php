<?php

namespace App\Models\Character;

use App\Models\Equipment\Inventory;
use App\Models\Magic\Spell;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * Class Character
 * @package App\Models\Character
 * @property int id
 * @property int campaign_id
 * @property int race_id
 * @property int|null subrace_id
 * @property int|null background_id
 * @property int|null owner_id
 * @property string name
 * @property string title
 * @property string type
 * @property string age
 * @property string alignment
 * @property bool dead
 * @property bool private
 * @property string bio
 * @property array ability_scores
 * @property string trait
 * @property string ideal
 * @property string bond
 * @property string flaw
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property Race race
 * @property Subrace subrace
 * @property Background background
 * @property User owner
 * @property Inventory inventories
 * @property Collection|CharacterClass[] classes
 * @property Collection|Subclass[] subclasses
 * @property Collection|Proficiency[] proficiencies
 * @property Collection|Language[] languages
 * @property Collection|Spell[] spells
 */
class Character extends Model
{
    protected $fillable = [
        'name', 'title', 'type', 'age', 'alignment', 'dead', 'bio', 'ability_scores', 'trait', 'ideal', 'bond', 'flaw'
    ];

    protected $casts = [
        'id' => 'int',
        'campaign_id' => 'int',
        'race_id' => 'int',
        'ability_scores' => 'array'
    ];

    public function race(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }

    public function subrace(): BelongsTo
    {
        return $this->belongsTo(Subrace::class);
    }

    public function background(): BelongsTo
    {
        return $this->belongsTo(Background::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function inventory(): BelongsTo
    {
        return $this->hasOne(Inventory::class);
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(CharacterClass::class, 'character_class', 'character_id', 'class_id')
            ->withPivot(['level', 'subclass_id']);
    }

    public function subclasses(): BelongsToMany
    {
        return $this->belongsToMany(Subclass::class, 'character_class', 'character_id', 'class_id')
            ->withPivot(['level']);
    }

    public function proficiencies(): BelongsToMany
    {
        return $this->belongsToMany(Proficiency::class)->withPivot(['origin_type', 'origin_id']);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class);
    }

    public function spells(): BelongsToMany
    {
        return $this->belongsToMany(Spell::class)->withPivot(['origin_type', 'origin_id']);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class)->withPivot(['feature_parent_id']);
    }
}
