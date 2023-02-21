<?php

namespace App\Http\Controllers\Api\Maps;

use App\Exceptions\UsedInOtherTableException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Maps\StoreRequest;
use App\Http\Requests\Maps\UpdateRequest;
use App\Http\Resources\Maps\MapCollection;
use App\Http\Resources\Maps\MapResource;
use App\Models\Map;
use App\Services\Maps\MapService;
use Illuminate\Http\JsonResponse;
use App\Models\RoleConst;
use Throwable;

class MapController extends Controller
{
    /**
     * MapController constructor.
     * @param MapService $mapService
     */
    public function __construct(private MapService $mapService)
    {
        $this->middleware(['permission:' . RoleConst::PERMISSION_PROCESSES_CRUD])->except(['show', 'update', 'getTypes']);
    }

    /**
     * Список диагностических карт
     *
     * Права: `crud processes`
     *
     * @group Диагностическая-карта
     *
     * @return MapCollection
     */
    public function index(): MapCollection
    {
        $maps = $this->mapService->getAll();

        return new MapCollection($maps);
    }

    /**
     * Получение диагностической карты
     *
     * Права: `crud processes`
     *
     * @group Диагностическая-карта
     *
     * @urlParam map integer required
     *
     * @param Map $map
     * @return JsonResponse
     */
    public function show(Map $map): JsonResponse
    {
        return response_json(['map' => MapResource::make($map)]);
    }

    /**
     * Добавление диагностической карты
     *
     * Права: `crud processes`
     *
     * @bodyParam data array[] required
     * @bodyParam data[].groups array
     * @bodyParam data[].groups[].title string
     * @bodyParam data[].groups[].items[].name string
     * @bodyParam data[].groups[].items[].type string
     *
     * @group Диагностическая-карта
     *
     * @param StoreRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $map = $this->mapService->store($request->validated());

        return response_json(['map' => MapResource::make($map)]);
    }

    /**
     * Обновление диагностической карты
     *
     * Права: `crud processes`
     *
     * @group Диагностическая-карта
     *
     * @bodyParam data array[] required
     * @bodyParam data[].groups array
     * @bodyParam data[].groups[].title string
     * @bodyParam data[].groups[].items[].name string
     * @bodyParam data[].groups[].items[].type string
     *
     * @urlParam map integer required
     *
     * @param UpdateRequest $request
     * @param int $mapId
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(UpdateRequest $request, int $mapId): JsonResponse
    {
        $map = $this->mapService->update($mapId, $request->validated());

        return response_json(['map' => MapResource::make($map)]);
    }

    /**
     * Удаление диагностической карты
     *
     * Права: `crud processes`
     *
     * Удаление только если нет связанных таблиц
     *
     * @group Диагностическая-карта
     *
     * @urlParam map integer required
     *
     * @param int $mapId
     * @return JsonResponse
     * @throws UsedInOtherTableException
     */
    public function destroy(int $mapId): JsonResponse
    {
        $this->mapService->delete($mapId);

        return response_success();
    }

    /**
     * Получение типов для вопросов в диагностической карте
     *
     * Права: `crud processes`
     *
     * @group Диагностическая-карта
     *
     * @urlParam map integer required
     */
    public function getTypes(): JsonResponse
    {
        return response_json([
            'types' => Map::types
        ]);
    }
}
