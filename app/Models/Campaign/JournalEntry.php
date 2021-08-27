<?php

namespace App\Models\Campaign;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int id
 * @property int campaign_id
 * @property string title
 * @property string content
 * @property int order
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class JournalEntry extends Model
{
    protected $table = 'journal';
    protected $fillable = ['title', 'content'];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
