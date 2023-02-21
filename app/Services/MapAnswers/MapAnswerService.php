<?php

namespace App\Services\MapAnswers;

use App\Exceptions\UsedInOtherTableException;
use App\Models\MapAnswer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Throwable;

class MapAnswerService
{
    /**
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator
    {
        return MapAnswer::with('task')
            ->orderBy('id', 'desc')
            ->paginate(30);
    }

    /**
     * @param  array  $data
     * @return MapAnswer
     * @throws Throwable
     */
    public function store(array $data): MapAnswer
    {
        return MapAnswer::create($data);
    }

    /**
     * @param  int  $mapId
     * @return MapAnswer
     */
    public function getMapById(int $mapId): MapAnswer
    {
        return MapAnswer::findOrFail($mapId);
    }

    /**
     * @param  int  $mapId
     * @param  array  $data
     * @return MapAnswer
     * @throws Throwable
     */
    public function update(int $mapId, array $data): MapAnswer
    {
        $map = $this->getMapById($mapId);

        $map->update($data);

        return $map;
    }

    /**
     * @param  int  $mapId
     * @throws UsedInOtherTableException
     */
    public function delete(int $mapId): void
    {
        try {
            MapAnswer::where('id', $mapId)->delete();
        } catch (Throwable) {
            throw new UsedInOtherTableException(
                'ДК нельзя удалить, так как он уже используется в других таблицах', 422
            );
        }
    }
}
