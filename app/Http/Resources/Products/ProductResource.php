<?php

namespace App\Http\Resources\Products;

use App\Http\Resources\Users\UserResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Product $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'place' => $this->place,
            'count' => $this->count,
            'input_sum' => $this->input_sum,
            'output_sum' => $this->output_sum,
            'description' => $this->description,
            'photo' => $this->photo_url,
            'sku' => $this->sku,
            'created_at' => db_to_date($this->created_at, 'd.m.Y H:i'),
            'updated_at' => db_to_date($this->updated_at, 'd.m.Y H:i'),
            'producer' => ProducerResource::make($this->producer),
            'storage' => StorageResource::make($this->storage),
            'user' => UserResource::make($this->user),
            'product_requests' => ProductRequestForProductResource::collection($this->productRequests),
        ];
    }
}
