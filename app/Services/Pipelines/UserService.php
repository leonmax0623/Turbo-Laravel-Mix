<?php

namespace App\Services\Pipelines;

use Illuminate\Database\Eloquent\Collection;

class UserService
{
    /**
     * @param int $pipelineId
     * @return array|Collection|\Illuminate\Support\Collection
     */
    public function getByPipelineId(int $pipelineId): array|Collection|\Illuminate\Support\Collection
    {
        $pipeline = (new PipelineService())->getPipelineById($pipelineId);

        return $pipeline->users ?? collect();
    }

    /**
     * @param int $pipelineId
     * @param int $userId
     * @return null
     */
    public function store(int $pipelineId, int $userId)
    {
        $pipeline = (new PipelineService())->getPipelineById($pipelineId);

        return $pipeline->users()->attach($userId);
    }

    /**
     * @param int $pipelineId
     * @param int $userId
     * @return int
     */
    public function delete(int $pipelineId, int $userId): int
    {
        $pipeline = (new PipelineService())->getPipelineById($pipelineId);

        return $pipeline->users()->detach($userId);
    }
}
