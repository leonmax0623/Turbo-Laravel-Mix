<?php

namespace App\Services\Maps;

use App\Exceptions\UsedInOtherTableException;
use App\Models\Map;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Throwable;

class MapService
{
    /**
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator
    {
        return Map::with('tasks')
            ->orderBy('id', 'desc')
            ->paginate(30);
    }

    /**
     * @param  array  $data
     * @return Map
     * @throws Throwable
     */
    public function store(array $data): Map
    {
        return Map::create($data);
    }

    /**
     * @param  int  $mapId
     * @return Map
     */
    public function getMapById(int $mapId): Map
    {
        return Map::findOrFail($mapId);
    }

    /**
     * @param  int  $mapId
     * @param  array  $data
     * @return Map
     * @throws Throwable
     */
    public function update(int $mapId, array $data): Map
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
            Map::where('id', $mapId)->delete();
        } catch (Throwable) {
            throw new UsedInOtherTableException(
                'ДК нельзя удалить, так как он уже используется в других таблицах', 422
            );
        }
    }
}
