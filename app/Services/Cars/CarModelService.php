<?php

namespace App\Services\Cars;

use App\Exceptions\UsedInOtherTableException;
use App\Models\Car;
use App\Models\CarModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Throwable;

class CarModelService
{
    /**
     * @param  array  $filter
     * @return LengthAwarePaginator
     */
    public function getPaginatedCarModels(array $filter): LengthAwarePaginator
    {
        $carModels = CarModel::with('carMark');

        if (!empty($filter['car_mark_id'])) {
            $carModels->where('car_mark_id', $filter['car_mark_id']);
        }

        return $carModels->orderBy('id', 'desc')->paginate(30);
    }

    /**
     * @param  array  $data
     * @return CarModel
     */
    public function store(array $data): CarModel
    {
        return CarModel::create($data);
    }

    /**
     * @param  int  $carModelId
     * @return CarModel
     */
    public function getCarModelById(int $carModelId): CarModel
    {
        return CarModel::findOrFail($carModelId);
    }

    /**
     * @param  int  $carModelId
     * @param  array  $data
     * @return CarModel
     */
    public function update(int $carModelId, array $data): CarModel
    {
        $carModel = $this->getCarModelById($carModelId);
        $carModel->update($data);

        return $carModel;
    }

    /**
     * @param  int  $carModelId
     * @throws UsedInOtherTableException
     */
    public function delete(int $carModelId): void
    {
        if (Car::where('car_model_id', $carModelId)->exists()) {
            throw new UsedInOtherTableException(
                'Модель нельзя удалить, так как существует авто этой модели', 422
            );
        }

        try {
            CarModel::where('id', $carModelId)->delete();
        } catch (Throwable) {
            throw new UsedInOtherTableException(
                'Модель нельзя удалить, так как она уже используется в других таблицах', 422
            );
        }
    }
}
