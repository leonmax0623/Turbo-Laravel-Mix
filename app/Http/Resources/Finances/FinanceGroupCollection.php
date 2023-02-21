<?php

namespace App\Http\Resources\Finances;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class FinanceGroupCollection extends ResourceCollection
{
    public $collects = FinanceGroupResource::class;

    public static $wrap = 'finance_groups';

    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
