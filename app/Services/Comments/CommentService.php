<?php

namespace App\Services\Comments;

use App\Exceptions\CustomValidationException;
use App\Models\ProcessTask;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Support\Collection;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class CommentService
{
    /**
     * @param string $modelAlias
     * @param int $modelId
     * @return Collection
     * @throws CustomValidationException
     */
    public function getAll(string $modelAlias, int $modelId): Collection
    {
        $modelClass = Comment::getModelClass($modelAlias);

        return Comment::with('user')->where('commentable_type', $modelClass)
            ->where('commentable_id', $modelId)->orderBy('id')->get();
    }

    /**
     * @param int $userId
     * @param string $modelAlias
     * @param int $modelId
     * @param array $data
     * @return Comment
     * @throws CustomValidationException
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function store(int $userId, string $modelAlias, int $modelId, array $data): Comment
    {
        Comment::getModel($modelAlias, $modelId);
        $modelClass = Comment::getModelClass($modelAlias);

        $data['commentable_type'] = $modelClass;
        $data['commentable_id'] = $modelId;
        $data['user_id'] = $userId;

        return DB::transaction(
            function () use ($data) {

                /** @var Comment $comment */
                $comment = Comment::create($data);

                foreach (request()->file('files', []) as $key => $file) {
                    $comment->addMediaFromRequest("files.$key")
                        ->toMediaCollection("files.$key", Comment::FILES_DISK);
                }

                return $comment;
            }
        );
    }
}
