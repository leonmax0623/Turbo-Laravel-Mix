<?php

namespace App\Http\Resources\Finances;

use App\Http\Resources\Departments\DepartmentResource;
use App\Http\Resources\Orders\OrderResource;
use App\Models\Finance;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinanceOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Finance $this */
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'operation_type'    => $this->operation_type,
            'payment_type'      => $this->payment_type,
            'sum'               => $this->sum,
            'total_paid_sum'    => $this->totalPaidSum,
            'total_pay_sum'     => $this->totalPaySum,
            'status'            => $this->status,
            'order_id'          => $this->order_id,
            'created_at'        => db_to_date($this->created_at, 'd.m.Y H:i'),
            'updated_at'        => db_to_date($this->updated_at, 'd.m.Y H:i'),
            'finance_group'     => FinanceGroupResource::make($this->financeGroup),
            'finance_logs'      => AtolLogsResource::collection($this->financeLogs),
            'department'        => DepartmentResource::make($this->department)
        ];
    }
}
