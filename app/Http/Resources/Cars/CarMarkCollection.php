<?php

namespace App\Http\Resources\Cars;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CarMarkCollection extends ResourceCollection
{
    public $collects = CarMarkResource::class;

    public static $wrap = 'car_marks';

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
