<?php

namespace App\Http\Resources\Pipelines;

use Illuminate\Http\Resources\Json\JsonResource;

class PipelineProcessTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'pipeline' => SimplePipelineResource::make($this->pipeline),
            'stage' => StageResource::make($this->stage),
            'created_at' => db_to_date($this->created_at, 'd.m.Y H:i'),
            'updated_at' => db_to_date($this->updated_at, 'd.m.Y H:i'),
        ];
    }
}
