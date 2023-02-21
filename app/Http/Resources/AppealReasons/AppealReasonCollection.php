<?php

namespace App\Http\Resources\AppealReasons;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AppealReasonCollection extends ResourceCollection
{
    public $collects = AppealReasonResource::class;

    public static $wrap = 'appeal_reasons';

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
