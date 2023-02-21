<?php

namespace App\Http\Resources\Maps;

use App\Http\Resources\Tasks\TaskResource;
use App\Models\Map;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MapResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Map $this */
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'data'       => $this->data,
            'tasks'      => TaskResource::collection($this->tasks),
            'created_at' => db_to_date($this->created_at, 'd.m.Y H:i'),
            'updated_at' => db_to_date($this->updated_at, 'd.m.Y H:i'),
        ];
    }
}
