<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Dictionary extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'dictionary';
    }
}
