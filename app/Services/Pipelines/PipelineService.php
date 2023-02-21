<?php

namespace App\Services\Pipelines;

use App\Exceptions\CustomValidationException;
use App\Exceptions\UsedInOtherTableException;
use App\Models\Pipeline;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

use Throwable;

class PipelineService
{
    /**
     * @param array $filter
     * @return Collection|SupportCollection
     * @throws CustomValidationException
     */
    public function getAll(array $filter): Collection|SupportCollection
    {
        $pipelines = Pipeline::with('stages');

        if ($departmentId = data_get($filter, 'department_id')) {
            $pipelines->where('department_id', $departmentId);
        } else {
            return collect();
        }

        if (data_get($filter, 'type')) {
            $model = Pipeline::getModelClass(data_get($filter, 'type'));
            $pipelines->where('type', $model);
        }

        return $pipelines->orderBy('order')->get();
    }

    /**
     * @param array $data
     * @return Pipeline
     * @throws CustomValidationException
     */
    public function store(array $data): Pipeline
    {
        if (mb_strtolower($data['name']) === 'личные задачи') {
            // @todo вынести определение типа на фронт
            $data['type'] = 'personal';
        }
        $data['type'] = $data['type'] ?? 'task';
        return Pipeline::create($data);
    }

    /**
     * @param int $departmentId
     * @return Pipeline
     * @throws CustomValidationException
     */
    public function createDefault(int $departmentId): Pipeline
    {
        return $this->store([
            'name'          => 'Личные задачи',
            'type'          => 'personal',
            'department_id' => $departmentId,
        ]);
    }

    /**
     * @param int $pipelineId
     * @return Pipeline
     */
    public function getPipelineById(int $pipelineId): Pipeline
    {
        return Pipeline::findOrFail($pipelineId);
    }

    /**
     * @param int $pipelineId
     * @param array $data
     * @return Pipeline
     */
    public function update(int $pipelineId, array $data): Pipeline
    {
        $pipeline     = $this->getPipelineById($pipelineId);
        $data['type'] = 'task'; // Pipeline::getModelClass(data_get($data, 'type'));
        $pipeline->update($data);

        return $pipeline;
    }

    /**
     * @param int $pipelineId
     * @throws UsedInOtherTableException
     */
    public function delete(int $pipelineId): void
    {
        try {
            Pipeline::where('id', $pipelineId)->delete();
        } catch (Throwable) {
            throw new UsedInOtherTableException(
                'Воронку нельзя удалить, так как она уже используется в других таблицах', 422
            );
        }
    }

    /**
     * @param array $ids
     * @return void
     */
    public function updateOrders(array $ids): void
    {
        $pipelines = Pipeline::whereIn('id', $ids)->get();

        $order = 0;

        foreach ($ids as $id) {
            if ($pipeline = $pipelines->firstWhere('id', $id)) {
                $pipeline->update(['order' => ++$order]);
            }
        }
    }
}
