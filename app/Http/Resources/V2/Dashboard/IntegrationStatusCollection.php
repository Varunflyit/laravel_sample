<?php

namespace App\Http\Resources\V2\Dashboard;

use App\Http\Resources\V2\Base\ResourceCollection;

class IntegrationStatusCollection extends ResourceCollection
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'integration_instances';

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
            'message' => trans('app.successfully_list_integration_status')
        ];
    }
}
