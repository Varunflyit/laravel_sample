<?php

namespace App\Http\Resources\V2\Product;

use App\Http\Resources\V2\Base\JsonResource;

class ProductAttributeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'label' => $this->label,
            'value' => $this->pivot->value,
        ];
    }
}
