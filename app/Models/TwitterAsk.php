<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TwitterAsk extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'tweet_id',
        'tweet',
        'keyword',
        'user',
        'is_replied',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_replied' => 'boolean',
    ];
}
