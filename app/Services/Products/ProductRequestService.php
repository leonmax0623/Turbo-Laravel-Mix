<?php

namespace App\Services\Products;

use App\Exceptions\UsedInOtherTableException;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ProductRequestService
{
    /**
     * @param array $filter
     * @return Collection
     */
    public function getAll(array $filter): Collection
    {
        $productRequest = ProductRequest::query();

        $productRequest->when(data_get($filter, 'status'), function (Builder $builder, $status) {
            return $builder->where('status', $status);
        });

        $productRequest->when(data_get($filter, 'order_id'), function (Builder $builder, $orderId) {
            return $builder->where('order_id', $orderId);
        });

        if (!Auth::user()->hasRole(['admin', 'director'])) {
            $productRequest->whereHas('product', function (Builder $builder) {
                $builder->whereHas('storage', function (Builder $query) {
                    return $query->where('department_id', Auth::user()->department_id);
                });
            });
        }

        return $productRequest->orderBy('id', 'desc')->get();
    }

    /**
     * @param  array  $data
     * @return ProductRequest
     */
    public function store(array $data): ProductRequest
    {
        $data['user_id'] = id();
        return ProductRequest::create($data);
    }

    /**
     * @param  int  $productRequestId
     * @return ProductRequest
     */
    public function getProductRequestById(int $productRequestId): ProductRequest
    {
        return ProductRequest::findOrFail($productRequestId);
    }

    /**
     * @param  int  $productRequestId
     * @param  array  $data
     * @return ProductRequest
     */
    public function update(int $productRequestId, array $data): ProductRequest
    {
        $data['user_id'] = id();
        $productRequest = $this->getProductRequestById($productRequestId);
        $productRequest->update($data);

        return $productRequest;
    }

    /**
     * @param int $productRequestId
     */
    public function delete(int $productRequestId): void
    {

        $this->getProductRequestById($productRequestId)->delete();
    }

    /**
     * @param ProductRequest $productRequest
     * @param array $data
     * @return ProductRequest
     * @throws UsedInOtherTableException
     */
    public function updateStatus(ProductRequest $productRequest, array $data): ProductRequest
    {
        $product = $this->getProduct($productRequest->product_id);

        if (
            $productRequest->status === ProductRequest::STATUS_DONE &&
            data_get($data, 'status') !== ProductRequest::STATUS_CANCEL
        ) {
            throw new UsedInOtherTableException(
                'Заявка уже выполнена', 422
            );
        }

        if (data_get($data, 'status') === ProductRequest::STATUS_DONE) {


            if ($product->count < $productRequest->count) {
                throw new UsedInOtherTableException(
                    'На складе не хватает товара.',
                    422
                );
            } elseif ($product->count >= $productRequest->count) {

                $product->count -= $productRequest->count;
                $product->save();

                $data['date_issue'] = now();
                $data['user_id'] = id();

            }

        }


        if (
            data_get($data, 'status') === ProductRequest::STATUS_CANCEL &&
            $productRequest->date_issue &&
            $productRequest->status === ProductRequest::STATUS_DONE
        ) {

            $product->count += $productRequest->count;
            $product->save();

            $data['date_issue'] = null;
        }

        $productRequest->update($data);

        return $productRequest;
    }

    public function getProduct(int $productId): Product|null
    {
        return Product::query()->findOrFail($productId);
    }
}
