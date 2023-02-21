<?php

namespace App\Http\Resources\Documents;

use App\Http\Resources\Orders\OrderResource;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Document $this */
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'comments'          => $this->comments,
//            'order'             => OrderResource::make($this->order),
            'document_template' => DocumentTemplateResource::make($this->documentTemplate),
            'created_at'        => db_to_date($this->created_at, 'd.m.Y H:i'),
            'updated_at'        => db_to_date($this->updated_at, 'd.m.Y H:i'),
        ];
    }
}
