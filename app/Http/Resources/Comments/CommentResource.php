<?php

namespace App\Http\Resources\Comments;

use App\Http\Resources\Media\SimpleMediaResource;
use App\Http\Resources\Users\SimpleUserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Comment;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Comment $this */
        return [
            'id'            => $this?->id,
            'description'   => $this->description,
            'user'          => SimpleUserResource::make($this->user),
            'model_id'      => $this->commentable_id,
            'model'         => Comment::getModelAlias($this->commentable_type),
            'files'         => SimpleMediaResource::collection($this->files),
            'created_at'    => db_to_date($this->created_at, 'd.m.Y H:i'),
            'updated_at'    => db_to_date($this->updated_at, 'd.m.Y H:i'),
        ];
    }
}
