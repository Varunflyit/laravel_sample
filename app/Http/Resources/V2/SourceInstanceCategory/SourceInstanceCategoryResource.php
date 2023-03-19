<?php

namespace App\Http\Resources\V2\SourceInstanceCategory;

use App\Http\Resources\V2\Base\JsonResource;

class SourceInstanceCategoryResource extends JsonResource
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
            'code' => $this->content_id,
            'label' => $this->content_name,
            'parent_id' => $this->parent_content_id,
            'has_children' => $this->children_count > 0 ? true : false,
            'parents' => !empty( $this->parents ) ? new SourceInstanceCategoryResource( $this->parents ) : [],
        ];
    }
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
            'message' => trans('app.successfully_list_category')
        ];
    }
}
