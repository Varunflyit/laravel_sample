<?php

namespace App\Http\Resources\V2\Base;

class BaseCollection extends ResourceCollection
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'data';

    /**
     * Get any additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'success' => true,
            'message' => 'success'
        ];
    }
}
