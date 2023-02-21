<?php

namespace App\Http\Controllers\Api\Products;

use App\Exceptions\UsedInOtherTableException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequests\IndexRequest;
use App\Http\Requests\ProductRequests\StoreRequest;
use App\Http\Requests\ProductRequests\UpdateRequest;
use App\Http\Requests\ProductRequests\UpdateStatusRequest;
use App\Http\Resources\Products\ProductRequestResource;
use App\Models\ProductRequest;
use App\Services\Products\ProductRequestService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use App\Models\RoleConst;

class ProductRequestController extends Controller
{
    /**
     * ProductRequestController constructor.
     * @param  ProductRequestService  $productRequest
     */
    public function __construct(private ProductRequestService $productRequest)
    {
        $this->middleware([
            'permission:' . RoleConst::PERMISSION_ORDERS_CRUD,
            'permission:' . RoleConst::PERMISSION_ORDERS_READ,
        ]);

        $this->middleware('permission:' . RoleConst::PERMISSION_STORAGES_CRUD, [
            'only' => 'updateStatus'
        ]);
    }

    /**
     * Список запрошенных запчастей
     *
     * Права: `crud orders`
     *
     * @group Заказ-наряды
     *
     * @param IndexRequest $request
     * @return JsonResponse
     */
    public function index(IndexRequest $request): JsonResponse
    {
        $productsRequests = $this->productRequest->getAll($request->validated());

        return response_json(['product_requests' => ProductRequestResource::collection($productsRequests)]);
    }

    /**
     * Получение запроса запчасти
     *
     * Права: `crud orders`
     *
     * @group Заказ-наряды
     *
     * @param int $productRequest
     * @return JsonResponse
     * @throws AuthorizationException
     *
     */
    public function show(int $productRequest): JsonResponse
    {
        $this->authorize('orders-read');

        // из-за названия модели laravel думает что это не модель, а класс наследуемый от Illuminate\Http\Request
        $productRequest = ProductRequest::find($productRequest);

        return response_json(['product_request' => ProductRequestResource::make($productRequest)]);
    }

    /**
     * Добавление запроса запчасти
     *
     * Название должно быть уникально
     *
     * Права: `crud orders`
     *
     * @group Заказ-наряды
     *
     * @param  StoreRequest  $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $productRequest = $this->productRequest->store($request->validated());

        return response_json(['product_request' => ProductRequestResource::make($productRequest)]);
    }

    /**
     * Обновление запроса запчасти
     *
     * Права: `crud orders`
     *
     * @group Заказ-наряды
     *
     * @param UpdateRequest $request
     * @param int $productRequestId
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, int $productRequestId): JsonResponse
    {
        $productRequest = $this->productRequest->update($productRequestId, $request->validated());

        return response_json(['product_request' => ProductRequestResource::make($productRequest)]);
    }

    /**
     * Удаление запроса запчасти
     *
     * Права: `crud orders`
     *
     * @group Заказ-наряды
     *
     * @urlParam productRequest integer required
     *
     * @param  int  $productRequestId
     * @return JsonResponse
     */
    public function destroy(int $productRequestId): JsonResponse
    {
        $this->productRequest->delete($productRequestId);

        return response_success();
    }

    /**
     * Обновление статуса запроса запчасти
     *
     * Права: `orders crud`
     *
     * @group Заказ-наряды
     *
     * @urlParam productRequest integer required
     *
     * @param UpdateStatusRequest $request
     * @param ProductRequest $productRequest
     * @return JsonResponse
     * @throws UsedInOtherTableException
     */
    public function updateStatus(UpdateStatusRequest $request, ProductRequest $productRequest): JsonResponse
    {

        $this->productRequest->updateStatus($productRequest, $request->validated());

        return response_json(['product_request' => ProductRequestResource::make($productRequest)]);
    }

    /**
     * Получение списка статусов
     *
     * Права: `orders crud`
     *
     * @group Заказ-наряды
     *
     * @return JsonResponse
     */
    public function getStatuses(): JsonResponse
    {
        return response_json(['statuses' => ProductRequest::getStatuses()]);
    }
}
