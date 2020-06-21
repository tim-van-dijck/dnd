<?php

namespace App\Models\Campaign;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class QuestObjective
 * @package App\Models\Campaign
 * @property int id
 * @property int quest_id
 * @property string name
 * @property bool optional
 * @property int status
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class QuestObjective extends Model
{
    const STATUS_OPEN = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_FAILED = 2;

    protected $fillable = ['status'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quest()
    {
        return $this->belongsTo(Quest::class);
    }
}