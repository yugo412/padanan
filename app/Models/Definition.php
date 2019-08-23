<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Definition extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'word_id',
        'text',
    ];

    /**
     * @return BelongsTo
     */
    public function word(): BelongsTo
    {
        return $this->belongsTo(Word::class);
    }
}
