<?php

namespace App\Http\Resources\Products;

use App\Http\Resources\Users\SimpleUserResource;
use App\Models\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductRequestForOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
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
            'created_at'    => db_to_date($this->created_at, 'd.m.Y H:i'),
            'updated_at'    => db_to_date($this->updated_at, 'd.m.Y H:i'),
        ];
    }
}
