<?php

namespace App\Http\Resources\Products;

use App\Http\Resources\Orders\OrderResource;
use App\Http\Resources\Users\SimpleUserResource;
use App\Models\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var ProductRequest $this */
        return [
            'id'            => $this->id,
            'count'         => $this->count,
            'sum'           => $this->sum,
            'total_sum'     => $this->totalSum,
            'date_issue'    => $this->date_issue,
            'status'        => $this->status,
            'product'       => ProductResource::make($this->product),
            'user'          => SimpleUserResource::make($this->user),
//            'order'         => OrderResource::make($this->order),
            'created_at'    => db_to_date($this->created_at, 'd.m.Y H:i'),
            'updated_at'    => db_to_date($this->updated_at, 'd.m.Y H:i'),
        ];
    }
}
