<?php

namespace App\Http\Controllers\Api\Tasks;

use App\Exceptions\CustomValidationException;
use App\Exceptions\UsedInOtherTableException;
use App\Http\Requests\Tasks\UpdatePipelineStageRequest;
use App\Models\RoleConst;
use App\Models\Stage;
use App\Services\Tasks\TaskService;
use Illuminate\Auth\Access\AuthorizationException;
use App\Services\Tasks\TaskPipelineService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Pipeline;
use App\Models\Task;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TaskPipelineController extends Controller
{
    /**
     * TaskPipelineController constructor.
     * @param  TaskPipelineService  $taskPipelineService
     */
    public function __construct(private TaskPipelineService $taskPipelineService) {}

    /**
     * Обновление этапа воронки задачи
     *
     * Права: `update tasks`
     *
     * @group Задачи
     *
     * @urlParam task integer required
     * @urlParam pipeline integer required
     * @bodyParam stage_id integer required
     *
     * @param UpdatePipelineStageRequest $request
     * @param Task $task
     * @param Pipeline $pipeline
     * @return JsonResponse
     * @throws CustomValidationException
     * @throws UsedInOtherTableException
     */
    public function updateStage(UpdatePipelineStageRequest $request, Task $task, Pipeline $pipeline): JsonResponse
    {
        (new TaskService)->checkStagePermission($task);

        $this->taskPipelineService->updateStage($task, $pipeline, $request->validated());

        return response_success();
    }

}
