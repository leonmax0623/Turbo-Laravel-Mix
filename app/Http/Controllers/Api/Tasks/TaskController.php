<?php

namespace App\Http\Controllers\Api\Tasks;

use App\Exceptions\CustomValidationException;
use App\Http\Requests\Tasks\AssignUserRequest;
use App\Http\Resources\Tasks\TaskWithFilesResource;
use App\Http\Resources\Tasks\TaskCollection;
use App\Models\RoleConst;
use Illuminate\Auth\Access\AuthorizationException;
use App\Exceptions\UsedInOtherTableException;
use App\Http\Requests\Tasks\StoreRequest;
use App\Http\Requests\Tasks\UpdateRequest;
use App\Http\Controllers\Controller;
use App\Services\Tasks\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Gate;
use Throwable;

class TaskController extends Controller
{
    /**
     * TaskController constructor.
     * @param TaskService $taskService
     */
    public function __construct(private TaskService $taskService)
    {
    }

    /**
     * Список задач
     *
     * Получение списка задач с пагинацией.
     * С помощью дополнительных параметров в url можно указать фрагмент для поиска по названию, по статусу,
     * по производителю, по заказу наряда, по воронке, по филиалу, период для даты начала, период для даты создания,
     * период для даты завершения.
     * Сортировка возможна по названию, статусу, id исполнителя, дате создания или дате завершения.
     *
     * Права: `read tasks` или `read department tasks` или `read own tasks`
     *
     * @urlParam Сортировка order string Значения: id, name, status, user_id, create_date, start_date, end_date. Если параметр
     * не указан, то по id desc.
     * @urlParam name string Поиск по фрагменту названия
     * @urlParam status string Поиск по статусу
     * @urlParam user_id integer Поиск по id исполнителя
     * @urlParam author_id integer Поиск по id владельца
     * @urlParam order_id integer Поиск по id заказ наряда
     * @urlParam pipeline_id integer Поиск по id воронки
     * @urlParam department_id integer Поиск по id филиала
     * @urlParam stage_id integer Поиск по stage_id воронки
     * @urlParam start_from datetime Поиск по start_at задачи от. Формат Y-m-d H:i:s
     * @urlParam start_to datetime Поиск по start_at задачи от. Формат Y-m-d H:i:s
     * @urlParam end_from datetime Поиск по end_at задачи от. Формат Y-m-d H:i:s
     * @urlParam end_to date datetime Поиск по end_at задачи от. Формат Y-m-d H:i:s
     * @urlParam created_from datetime Поиск по created_at задачи от. Формат Y-m-d H:i:s
     * @urlParam created_to datetime Поиск по created_at задачи до. Формат Y-m-d H:i:s
     * @urlParam is_map bool Поиск по дк
     *
     * @group Задачи
     *
     * @param Request $request
     * @return TaskCollection
     * @throws UsedInOtherTableException
     */
    public function index(Request $request): TaskCollection
    {

        if (
            !Gate::check(RoleConst::PERMISSION_TASKS_READ) &&
            !Gate::check(RoleConst::PERMISSION_TASKS_READ_DEPARTMENT) &&
            !Gate::check(RoleConst::PERMISSION_TASKS_READ_DEPARTMENT_AND_OWN) &&
            !Gate::check(RoleConst::PERMISSION_TASKS_READ_OWN)
        ) {
            throw new UsedInOtherTableException('У вас нет доступа к задачам', 422);
        }

        $filter = $request->all();

        if (!data_get($filter, 'history')) {
            $filter['history'] = 'index';
        }

        $tasks = $this->taskService->getPaginatedTasks($filter);

        return new TaskCollection($tasks);
    }

    /**
     * Список задач
     *
     * Дублируется index но добавляется ручной статус = done
     *
     * Права: `read tasks` или `read department tasks` или `read own tasks`
     *
     * @group Задачи
     *
     * @param Request $request
     * @return TaskCollection
     * @throws UsedInOtherTableException
     */
    public function history(Request $request): TaskCollection
    {

        if (
            !Gate::check(RoleConst::PERMISSION_TASKS_READ) &&
            !Gate::check(RoleConst::PERMISSION_TASKS_READ_DEPARTMENT) &&
            !Gate::check(RoleConst::PERMISSION_TASKS_READ_OWN)
        ) {
            throw new UsedInOtherTableException('У вас нет доступа к задачам', 422);
        }

        $filter = $request->all();
        $filter['status']  = 'done';
        $filter['history'] = 'history';

        $tasks = $this->taskService->getPaginatedTasks($filter);

        return new TaskCollection($tasks);
    }

    /**
     * Получение задачи
     *
     * @group Задачи
     *
     * @urlParam task integer required
     *
     * @param Task $task
     * @return JsonResponse
     * @throws UsedInOtherTableException
     */
    public function show(Task $task): JsonResponse
    {
        $this->authorize('view',$task);

        $taskResource = TaskWithFilesResource::make($task);

        return response_json(['task' => $taskResource]);
    }

    /**
     * Добавление задачи
     *
     * Права: `create tasks`
     *
     * @group Задачи
     *
     * @bodyParam temp_file_ids array[integer] Id временных файлов, загруженных отдельно до добавления задачи. Эти
     * временные файлы будут добавлены к задаче
     *
     * @param StoreRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws Throwable
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $this->authorize('create-tasks');

        $task = $this->taskService->store(id(), $request->validated());

        return response_json(['task' => TaskWithFilesResource::make($task)]);
    }

    /**
     * Обновление задачи
     *
     * Права: `update tasks`
     *
     * @group Задачи
     *
     * @urlParam task integer required
     * @bodyParam temp_file_ids array[integer] Id временных файлов, загруженных отдельно до обновления задачи. Эти
     * временные файлы будут добавлены к задаче
     * @bodyParam delete_file_ids array[integer] Id файлов задачи, которые необходимо удалить
     *
     * @param UpdateRequest $request
     * @param Task $task
     * @return JsonResponse
     * @throws UsedInOtherTableException
     * @throws CustomValidationException
     */
    public function update(UpdateRequest $request, Task $task): JsonResponse
    {
        $this->taskService->checkUpdatePermission($task);

        $task = $this->taskService->update(id(), $task, $request->validated());

        return response_json(['task' => TaskWithFilesResource::make($task)]);
    }

    /**
     * Удаление задачи
     *
     * Права: `delete tasks`
     *
     * @group Задачи
     *
     * @urlParam task integer required
     *
     * @param Task $task
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws UsedInOtherTableException
     */
    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete-tasks', $task);

        $this->taskService->delete($task);

        return response_success();
    }

    /**
     * Задачи по заказам
     *
     * Назначить ответственного в задачи
     *
     * Права: `update tasks`
     *
     * @group Задачи
     *
     * @bodyParam tasks array required
     * tasks [
     *  {
     *    user_id => 12
     *    task_id => 1
     *  }
     * ]
     *
     * @param AssignUserRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function assignUser(AssignUserRequest $request): JsonResponse
    {
        $this->taskService->assignUser($request->validated());

        return response_success();
    }


}
