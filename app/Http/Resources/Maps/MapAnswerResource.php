<?php

namespace App\Http\Resources\Maps;

use App\Models\MapAnswer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MapAnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var MapAnswer $this */
        return [
            'id'         => $this->id,
            'task_id'    => $this->task_id,
            'data'       => $this->data,
            'task'       => $this->task,
            'created_at' => db_to_date($this->created_at, 'd.m.Y H:i'),
            'updated_at' => db_to_date($this->updated_at, 'd.m.Y H:i'),
        ];
    }
}
