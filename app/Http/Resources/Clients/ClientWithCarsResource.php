<?php

namespace App\Http\Resources\Clients;

use App\Http\Resources\Cars\SimpleCarResource;
use App\Http\Resources\Departments\DepartmentResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientWithCarsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Client $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'middle_name' => $this->middle_name,
            'born_at' => db_to_date($this->born_at),
            'notes' => $this->notes,
            'address' => $this->address,
            'passport' => $this->passport,
            'phones' => $this->phones,
            'gender' => $this->gender,
            'emails' => $this->emails,
            'created_at' => db_to_date($this->created_at, 'd.m.Y H:i'),
            'updated_at' => db_to_date($this->updated_at, 'd.m.Y H:i'),
            'department' => DepartmentResource::make($this->department),
            'cars' => SimpleCarResource::collection($this->cars)
        ];
    }
}
