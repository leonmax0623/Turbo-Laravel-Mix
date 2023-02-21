<?php

namespace App\Http\Controllers\Api\Works;

use App\Http\Controllers\Controller;
use App\Http\Requests\Works\StoreRequest;
use App\Http\Requests\Works\UpdateRequest;
use App\Http\Resources\Works\WorkCollection;
use App\Http\Resources\Works\WorkResource;
use App\Models\RoleConst;
use App\Models\Work;
use App\Services\Works\WorkService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    /**
     * WorkController constructor.
     * @param  WorkService  $workService
     */
    public function __construct(private WorkService $workService)
    {
        $this->middleware(['permission:' . RoleConst::PERMISSION_WORKS_READ]);
    }

    /**
     * Список работ
     *
     * Права: `crud works`
     *
     * Упорядочены по id
     *
     * @group Работы
     *
     * @param Request $request
     * @return WorkCollection
     * @throws AuthorizationException
     */
    public function index(Request $request): WorkCollection
    {
        $works = $this->workService->getPaginatedWorks($request->all());

        return new WorkCollection($works);
    }

    /**
     * Получение работы
     *
     * Права: `crud works`
     *
     * @group Работы
     *
     * @param Work $work
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(Work $work): JsonResponse
    {
        return response_json(['work' => WorkResource::make($work)]);
    }

    /**
     * Добавление работы
     *
     * Права: `crud works`
     *
     * @group Работы
     *
     * @param  StoreRequest  $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $this->authorize('works-crud');

        $work = $this->workService->store($request->validated());

        return response_json(['work' => WorkResource::make($work)]);
    }

    /**
     * Обновление работы
     *
     * Права: `crud works`
     *
     * @group Работы
     *
     * @param  UpdateRequest $request
     * @param  int  $workId
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, int $workId): JsonResponse
    {
        $this->authorize('works-crud');

        $work = $this->workService->update($workId, $request->validated());

        return response_json(['work' => WorkResource::make($work)]);
    }

    /**
     * Удаление работы
     *
     * Права: `crud works`
     *
     * @group Работы
     *
     * @param  int  $workId
     * @return JsonResponse
     */
    public function destroy(int $workId): JsonResponse
    {
        $this->authorize('works-crud');

        $this->workService->delete($workId);

        return response_success();
    }

}
