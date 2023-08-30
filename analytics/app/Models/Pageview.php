<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pageview extends Model
{
    use HasFactory;

    protected $table = 'pageviews';

    /**
     * Get the Event that owns the pageview.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
