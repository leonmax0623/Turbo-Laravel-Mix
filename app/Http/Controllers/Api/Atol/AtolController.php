<?php

namespace App\Http\Controllers\Api\Atol;

use App\Http\Controllers\Controller;
use App\Services\Atol\AtolService;
use Illuminate\Http\JsonResponse;

class AtolController extends Controller
{
    /**
     * AtolController constructor.
     * @param AtolService $atolService
     */
    public function __construct(private AtolService $atolService) {}

    public function openShift(): JsonResponse
    {
        return response_json([
            'result' => $this->atolService->openShift()
        ]);
    }

    public function closeShift(): JsonResponse
    {
        return response_json([
            'result' => $this->atolService->closeShift()
        ]);
    }

}
