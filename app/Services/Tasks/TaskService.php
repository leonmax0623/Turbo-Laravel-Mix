<?php

namespace App\Services\Tasks;

use App\Models\Map;
use App\Models\ModelLog;
use App\Models\TaskMap;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Exceptions\CustomValidationException;
use App\Exceptions\UsedInOtherTableException;
use App\Models\Checkbox;
use App\Models\TempFile;
use App\Models\Stage;
use App\Models\User;
use App\Models\Task;
use App\Models\RoleConst;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class TaskService
{
    /**
     * @param array $filter
     * @return LengthAwarePaginator
     */
    public function getPaginatedTasks(array $filter): LengthAwarePaginator
    {

        $tasks = Task::with([
            'user', 'author', 'department',
            'order', 'checkboxes', 'pipelineTasks.pipeline',
            'pipelineTasks.stage'
        ]);

        $date = date('Y-m-d 00:00:01', strtotime('-1 week'));

        // 1970-01-01 23:59:59 = что бы брать по другим статусам все задачи
        if (data_get($filter, 'history') === 'history') {
            $tasks->where('updated_at', '<=', $date);
        } else if(data_get($filter, 'history') === 'index') {
            $tasks->whereRaw("updated_at >= IF(status = 'done', ?, '1970-01-01 00:00:01')", [$date]);
        }

        if (Gate::check(RoleConst::PERMISSION_TASKS_READ)) {
            // full access
        } elseif (Gate::check(RoleConst::PERMISSION_TASKS_READ_OWN)) {
            $tasks->where( fn ($query) => $query->where('user_id', id())->orWhere('author_id', id()));
        } elseif (Gate::check(RoleConst::PERMISSION_TASKS_READ_DEPARTMENT_AND_OWN)) {
            $tasks->where('department_id', user()->department_id);
        } elseif (Gate::check(RoleConst::PERMISSION_TASKS_READ_DEPARTMENT)) {
            $tasks
                ->where('department_id', user()->department_id)
                ->where(function ($query) {
                    $query
                        ->whereDoesntHave('pipelines', fn ($query) => $query->where('type', 'personal'))
                        ->orWhere( fn ($query) => $query->where('user_id', id())->orWhere('author_id', id()));
                });
        }


        if (!empty($filter['name'])) {
            $tasks->where('name', 'like', '%' . $filter['name'] . '%');
        }

        if (!empty($filter['status'])) {
            $tasks->where('status', $filter['status']);
        }

        if (!empty($filter['pipeline_id'])) {
            $tasks->whereHas('pipelineTasks', function ($query) use ($filter) {
                $query->where('pipeline_id', $filter['pipeline_id']);
            });
        }

        if (!empty($filter['stage_id'])) {
            $tasks->whereHas('pipelineTasks', function ($query) use ($filter) {
                $query->where('stage_id', $filter['stage_id']);
            });
        }

        if (!empty($filter['user_id'])) {
            $tasks->where('user_id', $filter['user_id']);
        }

        if (!empty($filter['author_id'])) {
            $tasks->where('author_id', $filter['author_id']);
        }

        if (!empty($filter['start_from']) || !empty($filter['start_to'])) {
            $tasks->whereBetween('start_at', [$filter['start_from'], $filter['start_to']]);
        }

        if (!empty($filter['end_from']) || !empty($filter['end_to'])) {
            $tasks->whereBetween('end_at', [$filter['end_from'], $filter['end_to']]);
        }

        if (!empty($filter['created_from']) && !empty($filter['created_to'])) {
            $tasks->whereBetween('created_at', [$filter['created_from'], $filter['created_to']]);
        } else if (empty($filter['created_from']) && !empty($filter['created_to'])) {
            $tasks->whereBetween('created_at', [$filter['created_from'], $filter['created_from']]);
        } else if (!empty($filter['created_from']) && empty($filter['created_to'])) {
            $tasks->whereBetween('created_at', [$filter['created_to'], $filter['created_to']]);
        }

        if (!empty($filter['order_id'])) {
            $tasks->where('order_id', $filter['order_id']);
        }

        if (!empty($filter['department_id'])) {
            $tasks->where('department_id', $filter['department_id']);
        }

        if (isset($filter['is_map'])) {
            $tasks->where('is_map', $filter['is_map']);
        }

        if (!empty($filter['order'])) {
            if ($filter['order'] === 'name') {
                $tasks->orderBy('name');
            } elseif ($filter['order'] === 'status') {
                $tasks->orderBy('status');
            } elseif ($filter['order'] === 'user_id') {
                $tasks->orderBy('user_id');
            } elseif ($filter['order'] === 'create_date') {
                $tasks->orderBy('created_at', 'desc');
            } elseif ($filter['order'] === 'start_date') {
                $tasks->orderBy('start_at');
            } elseif ($filter['order'] === 'end_date') {
                $tasks->orderBy('end_at');
            } else { // id or other
                $tasks->orderBy('id', 'desc');
            }
        } else {
            $tasks->orderBy('id', 'desc');
        }

        return $tasks->paginate(30);
    }

    /**
     * @param int $userId
     * @param Task $task
     * @param array|null $tempFileIds
     */
    private function storeFiles(int $userId, Task $task, ?array $tempFileIds): void
    {
        if (empty($tempFileIds)) {
            return;
        }

        $tempFiles = TempFile::where('user_id', $userId)->whereIn('id', $tempFileIds)->orderBy('id')->get();

        foreach ($tempFiles as $tempFile) {
            try {
                $task->addMedia($tempFile->getFirstMedia('temp')->getPath())
                    ->usingName($tempFile->getFirstMedia('temp')->name)
                    ->toMediaCollection('files', Task::FILES_DISK);
                $tempFile->delete();
            } catch (Throwable $e) {
                info('Не удалось добавить временный файл к задаче', [$e->getTraceAsString()]);
            }
        }
    }

    /**
     * @param int $userId
     * @return int|null
     */
    private function getUserDepartmentId(int $userId): int|null
    {
        return User::where('id', $userId)->firstOrFail()->department_id;
    }

    /**
     * @param int $pipelineId
     * @return int|null
     */
    private function getPipelineFirstStageId(int $pipelineId): ?int
    {
        return Stage::where('pipeline_id', $pipelineId)->first()->id ?? null;
    }

    /**
     * @param int $pipelineId
     * @param int $stageId
     * @return bool
     */
    private function isExistsPipelineStage(int $pipelineId, int $stageId): bool
    {
        return Stage::where('pipeline_id', $pipelineId)->where('id', $stageId)->exists();
    }

    /**
     * @param array $data
     * @return array
     * @throws CustomValidationException
     */
    private function preparePipelines(array $data): array
    {
        $pipelines = [];

        if (!empty(data_get($data, 'pipelines'))) {
            foreach (data_get($data, 'pipelines', []) as $pipelineData) {

                if (empty(data_get($pipelineData, 'pipeline_id'))) {
                    continue;
                }

                if (in_array(data_get($pipelineData, 'pipeline_id'), array_keys($pipelines))) {
                    throw new CustomValidationException('Несколько этапов из одной воронки не допустимы', 422);
                }

                if (empty(data_get($pipelineData, 'stage_id'))) {
                    $pipelines[data_get($pipelineData, 'pipeline_id')] =
                        ['stage_id' => $this->getPipelineFirstStageId(data_get($pipelineData, 'pipeline_id'))];
                    if (empty($pipelines[data_get($pipelineData, 'pipeline_id')])) {
                        throw new CustomValidationException('Недопустимые значения для воронки задачи', 422);
                    }
                } else {
                    if (!$this->isExistsPipelineStage(data_get($pipelineData, 'pipeline_id'), data_get($pipelineData, 'stage_id'))) {
                        throw new CustomValidationException('Недопустимые значения для воронки задачи', 422);
                    }

                    $pipelines[data_get($pipelineData, 'pipeline_id')] = ['stage_id' => data_get($pipelineData, 'stage_id')];
                }
            }
        }
        return $pipelines;
    }

    public function checkMap($mapId): bool
    {
        return Map::query()->where('id', $mapId)->exists();
    }

    /**
     * @param int $userId
     * @param array $data
     * @return Task
     * @throws Throwable
     */
    public function store(int $userId, array $data): Task
    {
        $data['author_id'] = $userId;
//        $data['department_id'] = $this->getUserDepartmentId($userId);
        $data['status'] = Task::STATUS_WAIT;

        $pipelines = $this->preparePipelines($data);

        return DB::transaction(function () use ($data, $userId, $pipelines) {

            /** @var Task $task */
            $task = Task::create($data);

            if (data_get($data, 'is_map') && data_get($data, 'map_id')) {
                TaskMap::query()->create([
                    'task_id' => $task->id,
                    'map_id' => data_get($data, 'map_id')
                ]);
            }

            foreach (data_get($data, 'checkboxes', []) as $checkbox) {
                if (empty(data_get($checkbox, 'description'))) continue;

                $checkbox = [
                    'description' => $checkbox['description'],
                    'task_id' => $task->id
                ];
                Checkbox::create($checkbox); // что бы логи записывались
            }

            if (!empty($pipelines)) {
                $task->pipelines()->attach($pipelines);
            }

            $this->storeFiles($userId, $task, data_get($data, 'temp_file_ids', []));

            return $task;
        });
    }

    /**
     * @param Task $task
     * @param array|null $ids
     */
    private function deleteTaskFiles(Task $task, ?array $ids): void
    {
        if (!empty($ids)) {
            foreach ($task->files as $file) {
                if (in_array($file->id, $ids)) {
                    $file->delete();
                }
            }
        }
    }

    /**
     * @param int $userId
     * @param Task $task
     * @param array $data
     * @return Task
     * @throws CustomValidationException
     */
    public function update(int $userId, Task $task, array $data): Task
    {
        $checkboxes = [];
        $newIds = [];
        foreach (data_get($data, 'checkboxes', []) as $checkbox) {
            $checkboxes[] = [
                'id' => $checkbox['id'] ?? null,
                'description' => $checkbox['description'] ?? '',
                'is_checked' => $checkbox['is_checked'] ?? 0,
                'task_id' => $task->id
            ];

            if (!empty($checkbox['id'])) {
                $newIds[] = $checkbox['id'];
            }
        }
        unset($data['checkboxes']);

        $ids = $task->checkboxes->pluck('id')->toArray();
        $deleteIds = [];
        foreach ($ids as $id) {
            if (!in_array($id, $newIds, true)) {
                $deleteIds[] = $id;
            }
        }

//        if ($task->user_id !== (int)$data['user_id']) {
//            $data['department_id'] = $this->getUserDepartmentId($userId);
//        }

        $pipelines = $this->preparePipelines($data);

        return DB::transaction(function () use ($userId, $task, $data, $checkboxes, $deleteIds, $pipelines) {
            $task->update($data);
            $task->checkboxes()->whereIn('id', $deleteIds)->delete();

            $isUpdate = $task->checkboxes()->upsert($checkboxes, ['id'], ['description', 'is_checked', 'task_id']);

            if ($isUpdate) {
                ModelLog::query()->create([
                    'model_type' => 'App\Models\Checkbox',
                    'model_id' => $task->id,
                    'data' => $checkboxes,
                    'type' => 'checkbox_updated',
                    'created_at' => now(),
                    'created_by' => auth()->id(),
                ]);
            }

            if (data_get($data, 'is_map') && data_get($data, 'map_id')) {

                $task->taskMap()->updateOrCreate([
                    ['task_id', $task->id]
                ],
                    [
                        'task_id' => $task->id,
                        'map_id' => data_get($data, 'map_id')
                    ]);

            } else if (empty(data_get($data, 'is_map')) && data_get($data, 'map_id')) {
                $task->taskMap()->delete();
            }

            if (!empty($pipelines)) {
                $task->pipelines()->sync($pipelines);
            }

            $this->deleteTaskFiles($task, $data['delete_file_ids'] ?? []);
            $this->storeFiles($userId, $task, $data['temp_file_ids'] ?? []);

            $task->refresh();

            return $task;
        });
    }

    /**
     * @param Task $task
     * @throws UsedInOtherTableException
     */
    public function delete(Task $task): void
    {
        try {
            $task->pipelineTasks()->delete();
            $task->mapAnswer()->delete();
            $task->delete();
        } catch (Throwable $e) {
            throw new UsedInOtherTableException(
                'Задачу нельзя удалить, так как она уже используется в других таблицах', 422
            );
        }
    }

    /**
     * @param array $data
     * @throws UsedInOtherTableException
     */
    public function assignUser(array $data): void
    {
        foreach (data_get($data, 'tasks', []) as $item) {

            $task = Task::query()->findOrFail(data_get($item, 'task_id'));

            $this->checkReadPermission($task);

            if ($task->id) {

                $task->update([
                    'user_id' => data_get($item, 'user_id')
                ]);

            }

        }

    }

    /**
     * @throws UsedInOtherTableException
     */
    public function checkReadPermission(?Task $task)
    {
        if (
            !Gate::check(RoleConst::PERMISSION_TASKS_READ) &&
            Gate::check(RoleConst::PERMISSION_TASKS_READ_DEPARTMENT) &&
            $task?->department_id !== auth()->user()->department_id
        ) {
            throw new UsedInOtherTableException("Эта задача в другом филиале. У вас нет на него доступа", 422);
        } else if (
            !Gate::check(RoleConst::PERMISSION_TASKS_READ) &&
            !Gate::check(RoleConst::PERMISSION_TASKS_READ_DEPARTMENT) &&
            Gate::check(RoleConst::PERMISSION_TASKS_READ_OWN) &&
            $task?->user_id !== id()
        ) {
            throw new UsedInOtherTableException("У вас нет доступа к задаче $task?->name", 422);
        } else if (
            !Gate::check(RoleConst::PERMISSION_TASKS_READ) &&
            !Gate::check(RoleConst::PERMISSION_TASKS_READ_DEPARTMENT) &&
            !Gate::check(RoleConst::PERMISSION_TASKS_READ_OWN)
        ) {
            throw new UsedInOtherTableException("У вас нет доступа к задачам", 422);
        }
    }

    /**
     * @throws UsedInOtherTableException
     */
    public function checkUpdatePermission(?Task $task)
    {
        if (
            !Gate::check(RoleConst::PERMISSION_TASKS_UPDATE) &&
            Gate::check(RoleConst::PERMISSION_TASKS_UPDATE_DEPARTMENT) &&
            $task?->department_id !== auth()->user()->department_id
        ) {
            throw new UsedInOtherTableException("Эта задача в другом филиале. У вас нет на него доступа", 422);
        } else if (
            !Gate::check(RoleConst::PERMISSION_TASKS_UPDATE) &&
            !Gate::check(RoleConst::PERMISSION_TASKS_UPDATE_DEPARTMENT) &&
            Gate::check(RoleConst::PERMISSION_TASKS_UPDATE_OWN) &&
            $task?->user_id !== id()
        ) {
            throw new UsedInOtherTableException("У вас нет доступа к задаче $task?->name", 422);
        } else if (
            !Gate::check(RoleConst::PERMISSION_TASKS_UPDATE) &&
            !Gate::check(RoleConst::PERMISSION_TASKS_UPDATE_DEPARTMENT) &&
            !Gate::check(RoleConst::PERMISSION_TASKS_UPDATE_OWN)
        ) {
            throw new UsedInOtherTableException("У вас нет доступа к задачам", 422);
        }
    }

    /**
     * @throws UsedInOtherTableException
     */
    public function checkStagePermission(?Task $task)
    {
        if (
            !Gate::check(RoleConst::PERMISSION_TASKS_STAGE) &&
            Gate::check(RoleConst::PERMISSION_TASKS_STAGE_DEPARTMENT) &&
            $task?->department_id !== auth()->user()->department_id
        ) {
            throw new UsedInOtherTableException("Эта задача в другом филиале. У вас нет на него доступа", 422);
        } else if (
            !Gate::check(RoleConst::PERMISSION_TASKS_STAGE) &&
            !Gate::check(RoleConst::PERMISSION_TASKS_STAGE_DEPARTMENT) &&
            Gate::check(RoleConst::PERMISSION_TASKS_STAGE_OWN) &&
            $task?->user_id !== id()
        ) {
            throw new UsedInOtherTableException("У вас нет доступа к задаче $task?->name", 422);
        } else if (
            !Gate::check(RoleConst::PERMISSION_TASKS_STAGE) &&
            !Gate::check(RoleConst::PERMISSION_TASKS_STAGE_DEPARTMENT) &&
            !Gate::check(RoleConst::PERMISSION_TASKS_STAGE_OWN)
        ) {
            throw new UsedInOtherTableException("У вас нет доступа к задачам", 422);
        }
    }
}
