<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Category extends Model implements HasMedia
{
    use SoftDeletes;
    use HasMediaTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'slug',
        'name',
        'description',
        'metadata',
        'is_published',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_published' => 'boolean',
        'metadata' => 'json',
        'deleted_at' => 'datetime',
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
