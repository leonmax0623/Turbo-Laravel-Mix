<?php

namespace App\Http\Resources\Orders;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Order $this */
        return [
            'id'                    => $this->id,
            'comment'               => $this->comment,
            'department_id'         => $this->department_id,
            'appeal_reason_id'      => $this->appeal_reason_id,
            'car_id'                => $this->car_id,
            'user_id'               => $this->user_id,
            'client_id'             => $this->client_id,
            'process_category_id'   => $this->process_category_id,
            'order_stage_id'        => $this->order_stage_id,
            'total_sum'             => $this->totalSum,
            'total_paid_sum'        => $this->totalPaidSum,
            'total_pay_sum'         => $this->totalPaySum,
            'created_at'            => db_to_date($this->created_at, 'd.m.Y H:i'),
            'updated_at'            => db_to_date($this->updated_at, 'd.m.Y H:i'),
        ];
    }
}
