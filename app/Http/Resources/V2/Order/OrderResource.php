<?php

namespace App\Http\Resources\V2\Order;

use App\Http\Resources\V2\Base\JsonResource;

class OrderResource extends JsonResource
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
            'grand_total' => $this->grand_total,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'integration_instance' => [
                'id' => $this->integration_instance_id,
                'name' => $this->integrationInstance->name
            ],
            'integration' => [
                'id' => $this->integrationInstance->integration_id,
                'name' => $this->integrationInstance->integration->name,
                'logo' => $this->integrationInstance->integration->icon
            ],
            'source' => [
                'platform' => $this->integrationInstance->source->source_platform,
                'order_id' => $this->source_id,
                'status' => $this->source_status
            ],
            'channel' => [
                'platform' => $this->integrationInstance->integration->channel_platform,
                'order_id' => $this->channel_id,
                'status' => $this->channel_status
            ],
            'order_lines' => OrderLineResource::collection($this->orderLines),
            'order_tracking' => (new OrderTrackingResource($this->orderTracking)),
            'log' => [
                'status' => $this->sync_status,
                'message' => $this->message,
                'lifecycle' => $this->sync_lifecycle,
                'action_required' => $this->action_required,
                'history' => SyncLogResource::collection($this->syncLogs)
            ]
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
            'message' => trans('app.successfully_retrieve_orders')
        ];
    }
}
