<?php

namespace App\Http\Controllers\Api\Cars;

use App\Exceptions\UsedInOtherTableException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CarModels\StoreRequest;
use App\Http\Requests\CarModels\UpdateRequest;
use App\Http\Resources\Cars\CarModelCollection;
use App\Http\Resources\Cars\CarModelResource;
use App\Services\Cars\CarModelService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\RoleConst;
use App\Models\CarModel;

class CarModelController extends Controller
{
    /**
     * CarModelController constructor.
     * @param  CarModelService  $carModelService
     */
    public function __construct(private CarModelService $carModelService)
    {
        $this->middleware(['permission:' . RoleConst::PERMISSION_CAR_MODELS_CRUD]);
    }

    /**
     * Список моделей автомобиля
     *
     * Права: `crud models`
     *
     * @group Автомобили
     *
     * @urlParam car_mark_id integer Получение моделей только указанной марки
     *
     * @param Request $request
     * @return CarModelCollection
     */
    public function index(Request $request): CarModelCollection
    {
        $carModels = $this->carModelService->getPaginatedCarModels($request->all());

        return new CarModelCollection($carModels);
    }

    /**
     * Получение модели автомобиля
     *
     * Права: `crud models`
     *
     * @group Автомобили
     *
     * @urlParam car_model integer required
     *
     * @param  CarModel  $carModel
     * @return JsonResponse
     */
    public function show(CarModel $carModel): JsonResponse
    {
        return response_json(['car_model' => CarModelResource::make($carModel)]);
    }

    /**
     * Добавление модели автомобиля
     *
     * Права: `crud models`
     *
     * @group Автомобили
     *
     * @param  StoreRequest  $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $carModel = $this->carModelService->store($request->validated());

        return response_json(['car_model' => CarModelResource::make($carModel)]);
    }

    /**
     * Обновление модели автомобиля
     *
     * Права: `crud models`
     *
     * @group Автомобили
     *
     * @urlParam car_model integer required
     *
     * @param  UpdateRequest  $request
     * @param  int  $carModelId
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, int $carModelId): JsonResponse
    {
        $carModel = $this->carModelService->update($carModelId, $request->validated());

        return response_json(['car_model' => CarModelResource::make($carModel)]);
    }

    /**
     * Удаление модели автомобиля
     *
     * Права: `crud models`
     *
     * Удаление только если нет автомобиля такой модели
     *
     * @group Автомобили
     *
     * @urlParam car_model integer required
     *
     * @param  int  $carModelId
     * @return JsonResponse
     * @throws UsedInOtherTableException
     */
    public function destroy(int $carModelId): JsonResponse
    {
        $this->carModelService->delete($carModelId);

        return response_success();
    }
}
