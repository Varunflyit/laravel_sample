<?php

namespace App\Http\Resources\V2\Base;

use Illuminate\Http\Resources\Json\JsonResource as BaseJsonResource;

class JsonResource extends BaseJsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'result';
}
