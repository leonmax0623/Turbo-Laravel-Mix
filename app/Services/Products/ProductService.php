<?php

namespace App\Services\Products;

use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Exceptions\UsedInOtherTableException;
use App\Models\Product;
use Throwable;
use DB;

class ProductService
{
    /**
     * @param array $filter
     * @return LengthAwarePaginator
     */
    public function getPaginatedProducts(array $filter): LengthAwarePaginator
    {
        $products = Product::with('producer');

        if (!empty(data_get($filter, 'name'))) {
            $products->where('name', 'like', '%' . data_get($filter, 'name') . '%');
        }

        if (!empty(data_get($filter, 'sku'))) {
            $products->where('sku', 'like', '%' . data_get($filter, 'sku') . '%');
        }

        if (!empty(data_get($filter, 'producer_id'))) {
            $products->where('producer_id', data_get($filter, 'producer_id'));
        }

        if (!empty(data_get($filter, 'status')) || !empty(data_get($filter, 'department_id'))) {

            $products->whereHas('productRequests', function (Builder $builder) use ($filter) {

                $builder->when(data_get($filter, 'status'), function (Builder $query, $status) {

                    $query->where('status', $status);

                });

            })->when(data_get($filter, 'department_id'), function (Builder $query, $departmentId) {

                $query->whereHas('storage', function (Builder $order) use ($departmentId) {
                    $order->where('department_id', $departmentId);
                });

            });
        }

        if (!empty(data_get($filter, 'storage_id'))) {
            $products->where('storage_id', data_get($filter, 'storage_id'));
        }

        if (!empty(data_get($filter, 'order'))) {
            if (data_get($filter, 'order') === 'name') {
                $products->orderBy('name');
            } else { // id or other
                $products->orderBy('id', 'desc');
            }
        } else {
            $products->orderBy('id', 'desc');
        }

        return $products->paginate(30);
    }

    /**
     * @param array $data
     * @return Product
     * @throws Throwable
     */
    public function store(array $data): Product
    {
        return DB::transaction(
            function () use ($data) {
                /** @var Product $product */
                $product = Product::create($data);
                if (request()->hasFile('photo')) {
                    $product->addMediaFromRequest('photo')
                        ->usingFileName(gen_file_name('photo'))
                        ->toMediaCollection('photo', Product::PHOTO_DISK);
                }
                return $product;
            }
        );
    }

    /**
     * @param int $productId
     * @return Product
     */
    public function getProductById(int $productId): Product
    {
        return Product::findOrFail($productId);
    }

    /**
     * @param int $productId
     * @param array $data
     * @return Product
     */
    public function update(int $productId, array $data): Product
    {
        $product = $this->getProductById($productId);
        $product->update($data);

        return $product;
    }

    /**
     * @param int $productId
     * @throws UsedInOtherTableException
     */
    public function delete(int $productId): void
    {
        try {
            $product = Product::query()->findOrFail($productId);
            $product->file->delete();
            $product->delete();
        } catch (Throwable) {
            throw new UsedInOtherTableException(
                'Товар нельзя удалить, так как он уже используется в других таблицах', 422
            );
        }
    }

    /**
     * @param Product $product
     * @return string
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function updatePhoto(Product $product): string
    {
        $product->addMediaFromRequest('photo')
            ->sanitizingFileName(function ($fileName) {
                return sanitize_file_name($fileName);
            })->usingFileName(gen_file_name('photo'))
            ->toMediaCollection('photo', Product::PHOTO_DISK);

        return $product->photo_url;
    }
}
