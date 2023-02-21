<?php

namespace App\Http\Resources\Documents\Generate;

use App\Models\Document;
use App\Models\ProductRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GenerateDocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Document $this */
        $order = $this->order;
        $productRequests = $order->productRequests;
        $works  = $order->works;
        $car    = $order->car;
        $client = $order->client;
        $user   = $order->user;
        $outputSum = 0;
        $productsCount = 0;
        $products = [];

        foreach ($productRequests as $productRequest) {
            /** @var ProductRequest $productRequest */
            $product = $productRequest?->product;

            if (!empty($product?->id)) {
                $product->setAttribute('request_count', $productRequest->count);
                $product->setAttribute('request_sum', $productRequest->sum);

                $products[] = $product;
                $outputSum += $productRequest->totalSum;
                $productsCount += $productRequest->count;
            }

        }

        return [
            'id'                            => $this->id,
            'order_id'                      => $order?->id,
            'order_created_at'              => db_to_date($order?->created_at),
            'order_client_surname'          => $client?->surname,
            'order_client_name'             => $client?->name,
            'order_client_middle_name'      => $client?->middle_name,
            'order_client_address'          => $client?->address,
            'order_user_surname'            => $user?->surname,
            'order_user_name'               => $user?->name,
            'order_user_middle_name'        => $user?->middle_name,
            'order_client_phones'           => implode(', ', data_get($order?->client, 'phones', [])),
            'order_client_phones_count'     => count(data_get($order?->client, 'phones', [])),
            'order_car_model_name'          => sprintf('%s %s', $car?->carModel?->carMark?->name, $car?->carModel?->name),
            'order_car_number'              => $car?->number,
            'order_car_vin'                 => $car?->vin,
            'order_car_year'                => $car?->year,
//            'order_car_miles'               => $order->car->miles,
            'comments'                      => $this?->comments,
            'order_appeal_reason_name'      => $order?->appealReason?->name,
            'order_total_sum'               => $order?->totalSum,
            'order_works'                   => $works,
            'order_works_total_sum'         => $works?->sum('sum'),
            'order_works_count'             => $works?->count(),
            'order_products_total_sum'      => $outputSum,
            'order_products_total_count'    => $productsCount,
            'order_product_requests'        => $order?->productRequests,
            'order_products'                => $products,
            'order_car_color'               => $car?->color,
            'order_car_body'                => $car?->body,
            'order_car_fuel_name'           => $car?->fuel?->name,
            'order_comments_description'    => $order?->comments?->implode('description', ', '),
        ];
    }
}
