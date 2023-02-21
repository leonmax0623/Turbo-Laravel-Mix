<?php

namespace App\Http\Resources\Works;

use App\Http\Resources\Orders\OrderResource;
use App\Http\Resources\Users\SimpleUserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'comments' => $this->comments,
            'sum' => $this->sum,
            'time' => $this->time,
            'user' => SimpleUserResource::make($this->user),
//            'order' => OrderResource::make($this->order),
            'created_at' => db_to_date($this->created_at, 'd.m.Y H:i'),
            'updated_at' => db_to_date($this->updated_at, 'd.m.Y H:i'),
        ];
    }
}
