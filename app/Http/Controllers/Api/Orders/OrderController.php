<?php

namespace App\Http\Controllers\Api\Orders;

use App\Exceptions\CustomValidationException;
use App\Exceptions\UsedInOtherTableException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\{
    UpdateRequest,
    StoreRequest,
    UpdateStageRequest,
};
use App\Http\Resources\Orders\{
    OrderAllResource,
    OrderResource,
};
use App\Models\{
    Order,
    RoleConst,
};
use App\Services\Orders\OrderService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService)
    {
        $this->middleware(['permission:' . RoleConst::PERMISSION_ORDERS_READ]);
    }

    /**
     * Список заказ-нарядов
     *
     * Права: `crud orders`
     *
     * Упорядочены по названию (name)
     *
     * @group Заказ-наряды
     *
     * @param Request $request
     * @return JsonResponse
     * @throws UsedInOtherTableException
     */
    public function index(Request $request): JsonResponse
    {

        if (!data_get($request, 'department_id')) {
            throw new UsedInOtherTableException(
                'Филиал не найден', 422
            );
        }

        $orders = $this->orderService->getAll($request->all());

        return response_json(['orders' => OrderAllResource::collection($orders)]);
    }

    /**
     * Получение заказ-наряда
     *
     * Права: `crud orders`
     *
     * @group Заказ-наряды
     *
     * @param Order $order
     * @return JsonResponse
     */
    public function show(Order $order): JsonResponse
    {
        return response_json(['order' => OrderResource::make($order)]);
    }

    /**
     * Добавление заказ-наряда
     *
     * Права: `crud orders`
     *
     * @group Заказ-наряды
     *
     * @param StoreRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $this->authorize('orders-crud');

        $order = $this->orderService->store($request->validated());

        return response_json(['order' => OrderResource::make($order)]);
    }

    /**
     * Обновление заказ-наряда
     *
     * Права: `crud orders`
     *
     * @group Заказ-наряды
     *
     * @param UpdateRequest $request
     * @param int $orderId
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateRequest $request, int $orderId): JsonResponse
    {
        $this->authorize('orders-crud');

        $order = $this->orderService->update($orderId, $request->validated());

        return response_json(['order' => OrderResource::make($order)]);
    }

    /**
     * Удаление заказ-наряда
     *
     * Права: `crud orders`
     *
     * @group Заказ-наряды
     *
     * @param int $orderId
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(int $orderId): JsonResponse
    {
        $this->authorize('orders-crud');

        $this->orderService->delete($orderId);

        return response_success();
    }

    /**
     *
     * Обновление этапа заказ-наряда
     *
     * Права: `crud orders`
     *
     * @group Заказ-наряды
     *
     * @urlParam order integer required
     * @urlParam pipeline integer required
     * @bodyParam stage_id integer required
     *
     * @param UpdateStageRequest $request
     * @param Order $order
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws CustomValidationException
     */
    public function updateStage(UpdateStageRequest $request, Order $order): JsonResponse
    {
        $this->authorize('orders-crud');

        $this->orderService->updateStage($order, $request->validated());

        return response_success();
    }

}
