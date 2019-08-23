<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tweet extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'word_id',
        'metadata',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'metadata' => 'json',
    ];

    /**
     * @return BelongsTo
     */
    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }
}
