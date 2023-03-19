<?php

namespace App\Http\Resources\V2\Product;

use App\Http\Resources\V2\Base\JsonResource;

class AttributeResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'label' => $this->label,
            'description' => $this->description,
            'type' => $this->type,
            'settings' => $this->settings,
            'created_at' => $this->created_at,
        ];
    }
}
