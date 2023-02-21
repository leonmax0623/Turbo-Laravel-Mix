<?php

namespace App\Http\Resources\Tasks;

use App\Http\Resources\ModelLogs\ModelLogResource;
use App\Http\Resources\Departments\DepartmentResource;
use App\Http\Resources\Media\SimpleMediaResource;
use App\Http\Resources\Orders\OrderTaskResource;
use App\Http\Resources\Users\SimpleUserResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskWithFilesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Task $this */
        return [
            'id'            => $this->id,
            'map_answer'    => $this->mapAnswer,
            'task_map'      => $this->taskMap,
            'name'          => $this->name,
            'description'   => $this->description,
            'status'        => $this->status,
            'position'      => $this->position,
            'is_map'        => $this->is_map,
            'start_at'      => db_to_date($this->start_at),
            'end_at'        => db_to_date($this->end_at),
            'deadline_at'   => db_to_date($this->deadline_at),
            'created_at'    => db_to_date($this->created_at, 'd.m.Y H:i'),
            'updated_at'    => db_to_date($this->updated_at, 'd.m.Y H:i'),
            'checkboxes'    => CheckboxResource::collection($this->checkboxes),
            'department'    => DepartmentResource::make($this->department),
            'order'         => OrderTaskResource::make($this->order),
            'author'        => SimpleUserResource::make($this->author),
            'user'          => SimpleUserResource::make($this->user),
            'files'         => SimpleMediaResource::collection($this->files),
            'pipelines'     => PipelineTaskResource::collection($this->pipelineTasks),
            'logs'          => ModelLogResource::collection($this->taskLogs),
        ];
    }
}
