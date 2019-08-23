<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Word extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'phrase',
        'metadata',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'metadata' => 'json',
    ];

    /**
     * @return HasMany
     */
    public function definitions(): HasMany
    {
        return $this->hasMany(Definition::class);
    }
}
