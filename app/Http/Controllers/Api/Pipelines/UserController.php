<?php

namespace App\Http\Controllers\Api\Pipelines;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stages\PipelineRequest;
use App\Http\Requests\Stages\PipelineUserRequest;
use App\Http\Resources\Users\SimpleUserResource;
use App\Services\Pipelines\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(private UserService $userService)
    {
    }

    /**
     * Список пользователей воронки
     *
     * Получение списка пользователей по id воронки
     *
     * @urlParam pipeline_id integer required ID воронки
     *
     * @group    Воронки
     *
     * @param PipelineRequest $request
     * @return JsonResponse
     */
    public function index(PipelineRequest $request): JsonResponse
    {
        $users = $this->userService->getByPipelineId($request->validated()['pipeline_id']);

        return response_json(['users' => SimpleUserResource::collection($users)]);
    }

    /**
     * Привязка пользователей к воронке
     *
     * @urlParam pipeline_id integer required ID воронки
     * @urlParam user_id integer required ID пользователя
     *
     * @group    Воронки
     *
     * @param PipelineUserRequest $request
     * @return Response
     */
    public function store(PipelineUserRequest $request): Response
    {
        $this->authorize('users-crud');

        $this->userService->store($request->get('pipeline_id'), $request->get('user_id'));

        return response()->noContent();
    }

    /**
     * Удаление пользователя из воронки
     *
     * @urlParam pipeline_id integer required ID воронки
     * @urlParam user_id integer required ID пользователя
     *
     * @group    Воронки
     *
     * @param PipelineUserRequest $request
     * @return Response
     */
    public function destroy(PipelineUserRequest $request): Response
    {
        $this->authorize('users-crud');

        $this->userService->delete($request->get('pipeline_id'), $request->get('user_id'));

        return response()->noContent();
    }
}
