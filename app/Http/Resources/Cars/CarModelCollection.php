<?php

namespace App\Http\Resources\Cars;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CarModelCollection extends ResourceCollection
{
    public $collects = CarModelResource::class;

    public static $wrap = 'car_models';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
