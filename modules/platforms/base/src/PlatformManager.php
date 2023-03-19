<?php

namespace Ecommify\Platform;

use Illuminate\Support\Manager;

class PlatformManager extends Manager
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return '';
    }
}
