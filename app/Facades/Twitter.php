<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Twitter extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'twitter';
    }
}
