<?php

namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStages\StoreOrderStageRequest;
use App\Http\Requests\OrderStages\UpdateOrderStageOrdersRequest;
use App\Http\Requests\OrderStages\UpdateOrderStageRequest;
use App\Http\Resources\Orders\OrderStageResource;
use App\Models\OrderStage;
use App\Models\RoleConst;
use App\Services\Orders\OrderStageService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Throwable;

class OrderStageController extends Controller
{
    public function __construct(private OrderStageService $orderStageService)
    {
        $this->middleware(['permission:' . RoleConst::PERMISSION_ORDERS_CRUD])->except(['index', 'show']);
        $this->middleware(['permission:' . RoleConst::PERMISSION_ORDERS_READ]);
    }

    /**
     * Список этапов для заказ-нарядов
     *
     * Права: `crud orders`
     *
     * Упорядочены по названию (name)
     *
     * @group Заказ-наряды
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $stages = $this->orderStageService->getAll();

        return response_json(['order_stages' => OrderStageResource::collection($stages)]);
    }

    /**
     * Получение этапа заказ-наряда
     *
     * Права: `crud orders`
     *
     * @group Заказ-наряды
     *
     * @param OrderStage $stage
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(OrderStage $order_stage): JsonResponse
    {
        return response_json(['order_stage' => OrderStageResource::make($order_stage)]);
    }

    /**
     * Добавление этапа заказ-наряда
     *
     * Права: `crud orders`
     *
     * @group Заказ-наряды
     *
     * @param StoreOrderStageRequest $request
     * @return JsonResponse
     */
    public function store(StoreOrderStageRequest $request): JsonResponse
    {
        $stage = $this->orderStageService->store($request->validated());

        return response_json(['order_stage' => OrderStageResource::make($stage)]);
    }

    /**
     * Обновление этапа заказ-наряда
     *
     * Права: `crud orders`
     *
     * @group Заказ-наряды
     *
     * @param UpdateOrderStageRequest $request
     * @param int $stageId
     * @return JsonResponse
     */
    public function update(UpdateOrderStageRequest $request, int $stageId): JsonResponse
    {
        $stage = $this->orderStageService->update($stageId, $request->validated());

        return response_json(['order_stage' => OrderStageResource::make($stage)]);
    }

    /**
     * Удаление этапа заказ-наряда
     *
     * Права: `crud orders`
     *
     * @group Заказ-наряды
     *
     * @param int $stageId
     * @return JsonResponse
     */
    public function destroy(int $stageId): JsonResponse
    {
        $this->orderStageService->delete($stageId);

        return response_success();
    }

    /**
     * Обновление позиций заказ-нарядов
     *
     * @param UpdateOrderStageOrdersRequest $request
     * @return JsonResponse
     */
    public function updateOrders(UpdateOrderStageOrdersRequest $request): JsonResponse
    {
        $ids = $request->get('ids');

        if (count($ids)) {
            $this->orderStageService->updateOrders($ids);
        }

        return response_success();
    }
}
