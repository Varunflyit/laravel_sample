<?php

namespace Ecommify\Platform\Facades;

use Illuminate\Support\Facades\Facade;

class Platform extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'platform';
    }
}
