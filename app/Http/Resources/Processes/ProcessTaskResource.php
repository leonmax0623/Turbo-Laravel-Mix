<?php

namespace App\Http\Resources\Processes;

use App\Http\Resources\Maps\MapResource;
use App\Http\Resources\Orders\OrderStageResource;
use App\Http\Resources\Pipelines\PipelineProcessTaskResource;
use App\Http\Resources\Roles\SimpleRoleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProcessTaskResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'role' => SimpleRoleResource::make($this->role),
            'time' => $this->time,
            'position' => $this->position,
            'pipelines' => PipelineProcessTaskResource::collection($this->pipelineProcessTasks),
            'process_checkboxes' => ProcessCheckboxResource::collection($this->processCheckboxes),
            'process_categories' => ProcessCategoryResource::collection($this->processCategories),
            'order_stage' => OrderStageResource::make($this->orderStage),
            'is_map' => $this->is_map,
            'map' => MapResource::make($this->map),
            'created_at' => db_to_date($this->created_at, 'd.m.Y H:i'),
            'updated_at' => db_to_date($this->updated_at, 'd.m.Y H:i'),
        ];
    }
}
