<?php

namespace App\Http\Resources\V2\Dashboard;

use App\Http\Resources\V2\Base\JsonResource;

class IntegrationStatusResource extends JsonResource
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
            'id' => $this->integration_instance_id,
            'name' => $this->name,
            'active_status' => $this->active_status == 'Y' ? trans('app.active') : trans('app.inactive'),
            'status' => $this->sync_status,
            'integration' => [
                'id' => $this->integration_id,
                'name' => $this->integration->name,
                'logo' => $this->integration->icon
            ],
            'orders' => [
                'synced' => [
                    '7_days' => $this->total_orders_last_7,
                    '14_days' => $this->total_orders_last_14,
                    '30_days' => $this->total_orders_last_30
                ],
                'order_value' => [
                    '7_days' => (float) $this->sum_orders_last_7,
                    '14_days' => (float) $this->sum_orders_last_14,
                    '30_days' => (float) $this->sum_orders_last_30
                ],
                'percent_order_value' => [
                    '7_days' => 0,
                    '14_days' => 0,
                    '30_days' => 0,
                ],
                'errors' => $this->failed_orders,
            ],
            'trackings' => [
                'synced' => [
                    '7_days' => $this->trackings_last_7,
                    '14_days' => $this->trackings_last_14,
                    '30_days' => $this->trackings_last_30,
                ],
                'errors' => $this->failed_trackings,
            ],
            'products' => [
                'synced' => [
                    '7_days' => 0,
                    '14_days' => 0,
                    '30_days' => 0,
                ],
                'errors' => 0,
            ],
        ];
    }
}
