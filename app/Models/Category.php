<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Category extends Model implements HasMedia
{
    use SoftDeletes;
    use HasMediaTrait;
    use Sluggable;

    /**
     * @var array
     */
    protected $fillable = [
        'slug',
        'name',
        'description',
        'metadata',
        'is_published',
        'is_default',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_published' => 'boolean',
        'is_default' => 'boolean',
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

    /**
     * @return array
     */
   public function sluggable(): array
   {
       return [
           'slug' => ['source' => 'name'],
       ];
   }

    /**
     * @return HasMany
     *@deprecated
     */
    public function terms(): HasMany
    {
        return $this->hasMany(Term::class);
    }
}
