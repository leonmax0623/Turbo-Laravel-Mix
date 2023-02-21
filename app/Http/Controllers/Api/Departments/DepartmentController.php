<?php

namespace App\Http\Controllers\Api\Departments;

use App\Exceptions\CustomValidationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Departments\StoreRequest;
use App\Http\Requests\Departments\UpdateRequest;
use App\Http\Resources\Departments\DepartmentResource;
use App\Models\Department;
use App\Models\RoleConst;
use App\Services\Departments\DepartmentCreator;
use App\Services\Departments\DepartmentService;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    /**
     * DepartmentController constructor.
     * @param  DepartmentService  $departmentService
     */
    public function __construct(private DepartmentService $departmentService)
    {
        $this->middleware(['permission:' . RoleConst::PERMISSION_DEPARTMENTS_CRUD]);
    }

    /**
     * Список филиалов
     *
     * Права: `crud departments`
     *
     * Упорядочены по названию (name)
     *
     * @group филиалы
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $departments = $this->departmentService->getAll();

        return response_json(['departments' => DepartmentResource::collection($departments)]);
    }

    /**
     * Получение филиала
     *
     * Права: `crud departments`
     *
     * @group Филиалы
     *
     * @param  Department  $department
     * @return JsonResponse
     */
    public function show(Department $department): JsonResponse
    {
        return response_json(['department' => DepartmentResource::make($department)]);
    }

    /**
     * Добавление филиала
     *
     * Права: `crud departments`
     *
     * @group Филиалы
     *
     * @param StoreRequest $request
     * @param DepartmentCreator $departmentCreator
     * @return JsonResponse
     * @throws CustomValidationException
     */
    public function store(StoreRequest $request, DepartmentCreator $departmentCreator): JsonResponse
    {
        $department = $departmentCreator->handle($request->validated());

        return response_json(['department' => DepartmentResource::make($department)]);
    }

    /**
     * Обновление филиала
     *
     * Права: `crud departments`
     *
     * @group Филиаллы
     *
     * @param  UpdateRequest  $request
     * @param  int  $departmentId
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, int $departmentId): JsonResponse
    {
        $department = $this->departmentService->update($departmentId, $request->validated());

        return response_json(['department' => DepartmentResource::make($department)]);
    }

    /**
     * Удаление филиала
     *
     * Права: `crud departments`
     *
     * Soft delete
     *
     * @group Филиалы
     *
     * @param  int  $departmentId
     * @return JsonResponse
     */
    public function destroy(int $departmentId): JsonResponse
    {
        $this->departmentService->delete($departmentId);

        return response_success();
    }
}
