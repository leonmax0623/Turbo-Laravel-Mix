<?php

namespace App\Http\Resources\Finances;

use App\Models\AtolLog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AtolLogsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var AtolLog $this */
        return [
            'id'                => $this->id,
            'data'              => $this->data,
            'operation_type'    => $this->operation_type,
            'sum'               => $this->sum,
            'status'            => $this->status,
        ];
    }
}
