<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'query',
        'metadata',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'metadata' => 'json',
    ];
}
