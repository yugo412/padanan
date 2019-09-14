<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'channel',
        'metadata',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'metadata' => 'json',
        'deleted_at' => 'datetime',
    ];

    /**
     * @return MorphTo
     */
    public function postable(): MorphTo
    {
        return $this->morphTo();
    }
}
