<?php

namespace Skegel13\Larablocks\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Skegel13\Larablocks\Larablocks
 */
class Larablocks extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Skegel13\Larablocks\Larablocks::class;
    }
}
