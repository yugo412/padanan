<?php

namespace App\Repositories;

use Torann\LaravelRepository\Repositories\AbstractRepository;

class Category extends AbstractRepository
{
    /**
     * @var string
     */
    protected $model = \App\Models\Category::class;

    /**
     * @var array
     */
    protected $orderable = [
        'name',
    ];
}
