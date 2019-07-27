<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Word extends Model
{
    use Sluggable;

    /**
     * @var array
     */

    protected $with = ['category'];

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'slug',
        'origin',
        'locale',
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @param string $origin
     */
    public function setOriginAttribute(string $origin): void
    {
        $this->attributes['origin'] = strtolower($origin);
    }

    /**
     * @param string $locale
     */
    public function setLocaleAttribute(string $locale): void
    {
        $this->attributes['locale'] = strtolower($locale);
    }

    /**
     * @return string
     */
    public function getOriginAttribute(): string
    {
        return strtolower($this->attributes['origin']);
    }

    /**
     * @return string
     */
    public function getLocaleAttribute(): string
    {
        return strtolower($this->attributes['locale']);
    }

    /**
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => ['source' => 'locale'],
        ];
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
