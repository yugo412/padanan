<?php

namespace App\Models;

use App\User;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
        'source',
        'total_likes',
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

    /**
     * @return MorphMany
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return MorphMany
     */
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /**
     * @param Builder $builder
     * @param string $keyword
     * @return Builder
     */
    public function scopeSearch(Builder $builder, string $keyword): Builder
    {
        return $builder->whereRaw('MATCH (origin, locale) AGAINST (\''.$keyword.'\' IN BOOLEAN MODE) > 0');
    }
}
