<?php

namespace App\Http\Resources\Processes;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class ProcessCategoryCollection extends ResourceCollection
{
    public $collects = ProcessCategoryResource::class;

    public static $wrap = 'process_categories';

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
