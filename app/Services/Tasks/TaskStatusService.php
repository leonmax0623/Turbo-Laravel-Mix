<?php

namespace App\Services\Tasks;

use App\Models\ModelLog;
use App\Models\Task;

class TaskStatusService
{
    /**
     * @param  Task  $task
     * @param  array  $data
     * @return Task
     */
    public function updateStatusToProcess(Task $task, array $data): Task
    {
        if (empty($data['start_at'])) {
            $data['start_at'] = now()->toDateString();
        }

        $data['status'] = Task::STATUS_PROCESS;

        $task->update($data);
        $this->logging(['status' => Task::STATUS_PROCESS], $task);
        return $task;
    }

    /**
     * @param  Task  $task
     * @param  array  $data
     * @return Task
     */
    public function updateStatusToDone(Task $task, array $data): Task
    {
        if (empty($data['end_at'])) {
            $data['end_at'] = now()->toDateString();
        }

        $data['status'] = Task::STATUS_DONE;

        $task->update($data);
        $this->logging(['status' => Task::STATUS_DONE], $task);
        return $task;
    }

    /**
     * @param  Task  $task
     * @return Task
     */
    public function updateStatusToPause(Task $task): Task
    {
        $task->update(['status' => Task::STATUS_PAUSE]);
        $this->logging(['status' => Task::STATUS_PAUSE], $task);
        return $task;
    }

    /**
     * @param  Task  $task
     * @param  array  $data
     * @return Task
     */
    public function updateStatus(Task $task, array $data): Task
    {
        $task->update($data);

        if (data_get($data, 'status')) {
            $this->logging(['status' => data_get($data, 'status')], $task);
        }

        return $task;
    }

    public function logging(array $data, ?Task $task): void
    {

        if ($task->pipelineTasks?->count('task_id') > 0) {
            $data['pipelines'] = [];
            foreach ($task->pipelineTasks as $pipelineTask) {
                $data['pipelines'][] = [
                    'pipeline_id' => $pipelineTask?->pipeline_id,
                    'stage_id' => $pipelineTask?->stage_id,
                ];
            }
        }

        ModelLog::query()->create([
            'model_type'    => 'App\Models\Task',
            'model_id'      => $task->id,
            'data'          => $data,
            'type'          => 'task_status',
            'created_at'    => now(),
            'created_by'    => auth()->id(),
        ]);
    }
}
