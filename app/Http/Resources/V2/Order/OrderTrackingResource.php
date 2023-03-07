<?php

namespace App\Http\Resources\V2\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderTrackingResource extends JsonResource
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
            'shipping_service' => $this->shipping_service,
            'carrier' => $this->carrier,
            'carrier_tracking' => $this->carrier_tracking,
            'carrier_tracking_status' => $this->carrier_tracking_status,
        ];
    }
}
