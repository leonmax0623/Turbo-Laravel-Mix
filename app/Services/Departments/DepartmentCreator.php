<?php

namespace App\Services\Departments;

use App\Exceptions\CustomValidationException;
use App\Models\Department;
use App\Services\Pipelines\PipelineService;
use App\Services\Pipelines\StageService;

class DepartmentCreator
{
    public function __construct(
        private DepartmentService $departmentService,
        private PipelineService $pipelineService,
        private StageService $stageService
    ) {
    }

    /**
     * @param array $data
     * @return Department
     * @throws CustomValidationException
     */
    public function handle(array $data): Department
    {
        $department = $this->departmentService->store([
            'name' => $data['name'],
        ]);

        $pipeline = $this->pipelineService->createDefault($department->id);

        $this->stageService->createDefault($pipeline->id);

        return $department;
    }
}
