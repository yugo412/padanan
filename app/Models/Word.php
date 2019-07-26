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
