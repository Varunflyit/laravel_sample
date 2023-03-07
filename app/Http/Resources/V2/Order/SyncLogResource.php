<?php

namespace App\Http\Resources\V2\Order;

use Ecommify\Integration\Mappings\SyncOptions\SyncStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class SyncLogResource extends JsonResource
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
            'message' => $this->message,
            'result' => $this->is_success ? SyncStatus::ORDER_SYNC_COMPLETE : SyncStatus::ORDER_SYNC_FAILED,
            'errors' => $this->is_success ? [] : $this->response,
            'warnings' => [],
            'created_at' => $this->created_at
        ];
    }
}
