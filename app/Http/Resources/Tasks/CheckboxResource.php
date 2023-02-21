<?php

namespace App\Http\Resources\Tasks;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckboxResource extends JsonResource
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
            'id' => $this->id,
            'description' => $this->description,
            'is_checked' => $this->is_checked,
            'task_id' => $this->task_id,
        ];
    }
}
