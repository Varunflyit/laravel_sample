<?php

namespace App\Http\Resources\V2\Product;

use App\Http\Resources\V2\Base\ResourceCollection;

class AttributeCollection extends ResourceCollection
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'product_attributes';

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
            'message' => trans('app.successfully_list_products')
        ];
    }
}
