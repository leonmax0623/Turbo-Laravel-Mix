<?php

namespace App\Http\Resources\Processes;

use App\Http\Resources\AppealReasons\AppealReasonResource;
use App\Models\ProcessCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProcessCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var ProcessCategory $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'count_tasks' => $this->processTasks?->count(),
            'created_at' => db_to_date($this->created_at, 'd.m.Y H:i'),
            'updated_at' => db_to_date($this->updated_at, 'd.m.Y H:i'),
            'appeal_reason' => AppealReasonResource::make($this->appealReason)
        ];
    }
}
