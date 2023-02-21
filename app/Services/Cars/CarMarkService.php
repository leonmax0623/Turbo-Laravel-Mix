<?php

namespace App\Services\Cars;

use App\Exceptions\UsedInOtherTableException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\CarMark;
use App\Models\CarModel;
use Throwable;

class CarMarkService
{
    /**
     * @return LengthAwarePaginator
     */
    public function getPaginatedCarMarks(): LengthAwarePaginator
    {
        return CarMark::orderBy('id', 'desc')->paginate(30);
    }

    /**
     * @param  array  $data
     * @return CarMark
     */
    public function store(array $data): CarMark
    {
        return CarMark::create($data);
    }

    /**
     * @param  int  $carMarkId
     * @return CarMark
     */
    public function getCarMarkById(int $carMarkId): CarMark
    {
        return CarMark::findOrFail($carMarkId);
    }

    /**
     * @param  int  $carMarkId
     * @param  array  $data
     * @return CarMark
     */
    public function update(int $carMarkId, array $data): CarMark
    {
        $carMark = $this->getCarMarkById($carMarkId);
        $carMark->update($data);

        return $carMark;
    }

    /**
     * @param  int  $carMarkId
     * @throws UsedInOtherTableException
     */
    public function delete(int $carMarkId): void
    {
        if (CarModel::where('car_mark_id', $carMarkId)->exists()) {
            throw new UsedInOtherTableException(
                'Марку нельзя удалить, так как существует модель авто с этой маркой', 422
            );
        }

        try {
            CarMark::where('id', $carMarkId)->delete();
        } catch (Throwable) {
            throw new UsedInOtherTableException(
                'Марку нельзя удалить, так как она уже используется в других таблицах', 422
            );
        }
    }
}
