<?php

namespace App\Http\Resources\Products;

use App\Http\Resources\Departments\DepartmentResource;
use App\Models\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StorageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Storage $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => db_to_date($this->created_at, 'd.m.Y H:i'),
            'updated_at' => db_to_date($this->updated_at, 'd.m.Y H:i'),
            'department' => DepartmentResource::make($this->department)
        ];
    }
}
