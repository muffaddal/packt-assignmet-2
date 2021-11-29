<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PacktAPIFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'packtapi';
    }
}
