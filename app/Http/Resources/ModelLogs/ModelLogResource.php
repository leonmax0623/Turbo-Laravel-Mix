<?php

namespace App\Http\Resources\ModelLogs;

use App\Http\Resources\Users\UserResource;
use App\Models\ModelLog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModelLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var ModelLog $this */
        return [
            'id'            => $this->id,
            'model_type'    => $this->model_type,
            'model_id'      => $this->model_id,
            'data'          => $this->data,
            'type'          => $this->type,
            'created_at'    => $this->created_at,
            'created_by'    => $this->created_by,
            'created_user'  => UserResource::make($this->createdBy),
//            'model'         => $this->modelType
        ];
    }
}
