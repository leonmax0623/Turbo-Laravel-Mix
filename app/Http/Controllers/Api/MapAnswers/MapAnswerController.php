<?php

namespace App\Http\Controllers\Api\MapAnswers;

use App\Exceptions\UsedInOtherTableException;
use App\Http\Controllers\Controller;
use App\Http\Requests\MapAnswers\StoreRequest;
use App\Http\Requests\MapAnswers\UpdateRequest;
use App\Http\Resources\Maps\MapAnswerResource;
use App\Models\MapAnswer;
use App\Services\MapAnswers\MapAnswerService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Throwable;

class MapAnswerController extends Controller
{
    /**
     * MapAnswerController constructor.
     * @param  MapAnswerService  $mapAnswerService
     */
    public function __construct(private MapAnswerService $mapAnswerService) {}

    /**
     * Список вопросов
     *
     * Список вопросов диагностической карты
     *
     * Права: `crud processes`
     *
     * @group Диагностическая-карта
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $mapAnswers = $this->mapAnswerService->getAll();

        return response_json(['map_answers' => MapAnswerResource::collection($mapAnswers)]);
    }

    /**
     * Получение ответа
     *
     * Получение ответа диагностической карты
     *
     * Права: `crud processes`
     *
     * @group Диагностическая-карта
     *
     * @urlParam map_answer integer required
     *
     * @param  MapAnswer  $mapAnswer
     * @return JsonResponse
     */
    public function show(MapAnswer $mapAnswer): JsonResponse
    {
        return response_json(['map_answer' => MapAnswerResource::make($mapAnswer)]);
    }

    /**
     * Добавление ответа
     *
     * Добавление ответа диагностической карты
     *
     * Права: `crud processes`
     *
     * @bodyParam data array[] required
     * @bodyParam data[].groups array
     * @bodyParam data[].groups[].title string
     * @bodyParam data[].groups[].items[].name string
     * @bodyParam data[].groups[].items[].answer string
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
        $this->authorize('create-tasks');

        $mapAnswer = $this->mapAnswerService->store($request->validated());

        return response_json(['map_answer' => MapAnswerResource::make($mapAnswer)]);
    }

    /**
     * Обновление ответа
     *
     * Обновление ответа диагностической карты
     *
     * Права: `crud processes`
     *
     * @group Диагностическая-карта
     *
     * @bodyParam data array[] required
     * @bodyParam data[].groups array
     * @bodyParam data[].groups[].title string
     * @bodyParam data[].groups[].items[].name string
     * @bodyParam data[].groups[].items[].answer string
     * @bodyParam data[].groups[].items[].type string
     *
     * @urlParam map_answer integer required
     *
     * @param UpdateRequest $request
     * @param int $mapAnswerId
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(UpdateRequest $request, int $mapAnswerId): JsonResponse
    {
        $mapAnswer = $this->mapAnswerService->update($mapAnswerId, $request->validated());

        return response_json(['map_answer' => MapAnswerResource::make($mapAnswer)]);
    }

    /**
     * Удаление ответа
     *
     * Удаление ответа диагностической карты
     *
     * Права: `crud processes`
     *
     * Удаление только если нет связанных таблиц
     *
     * @group Диагностическая-карта
     *
     * @urlParam map_answer integer required
     *
     * @param int $mapAnswerId
     * @return JsonResponse
     * @throws UsedInOtherTableException
     * @throws AuthorizationException
     */
    public function destroy(int $mapAnswerId): JsonResponse
    {
        $this->authorize('delete-tasks');

        $this->mapAnswerService->delete($mapAnswerId);

        return response_success();
    }
}
