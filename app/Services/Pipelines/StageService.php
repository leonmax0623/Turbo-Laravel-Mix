<?php

namespace App\Services\Pipelines;

use App\Exceptions\UsedInOtherTableException;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

class StageService
{
    /**
     * @param int $pipelineId
     * @return array|Collection|\Illuminate\Support\Collection
     */
    public function getByPipelineId(int $pipelineId): array|Collection|\Illuminate\Support\Collection
    {
        return Stage::where('pipeline_id', $pipelineId)->orderBy('order')->get();
    }

    /**
     * @param array $data
     * @return Stage
     */
    public function store(array $data): Stage
    {
        return Stage::create($data);
    }

    /**
     * @param int $pipelineId
     * @return \Illuminate\Support\Collection
     */
    public function createDefault(int $pipelineId): \Illuminate\Support\Collection
    {
        $stages = collect();

        foreach ($this->getDefaultStagesData() as $data) {
            $data['pipeline_id'] = $pipelineId;

            $stages->push($this->store($data));
        }

        return $stages;
    }

    /**
     * @return array[]
     */
    public function getDefaultStagesData(): array
    {
        return [
            [
                'name'  => 'Заявка',
                'color' => '#ededed',
            ],
            [
                'name'  => 'Ожидает согласования',
                'color' => '#f8ffc7',
            ],
            [
                'name'  => 'В работе',
                'color' => '#96d35f',
            ],
        ];
    }

    /**
     * @param int $stageId
     * @return Stage
     */
    public function getStageById(int $stageId): Stage
    {
        return Stage::findOrFail($stageId);
    }

    /**
     * @param int $stageId
     * @param array $data
     * @return Stage
     */
    public function update(int $stageId, array $data): Stage
    {
        $stage = $this->getStageById($stageId);
        $stage->update($data);

        return $stage;
    }

    /**
     * @param int $stageId
     * @throws UsedInOtherTableException
     */
    public function delete(int $stageId): void
    {
        try {
            Stage::where('id', $stageId)->delete();
        } catch (Throwable) {
            throw new UsedInOtherTableException(
                'Этап нельзя удалить, так как он уже используется в других таблицах', 422
            );
        }
    }

    /**
     * @param array $ids
     * @return void
     */
    public function updateOrders(array $ids): void
    {
        $stages = Stage::whereIn('id', $ids)->get();

        $order = 0;

        foreach ($ids as $id) {
            if ($stage = $stages->firstWhere('id', $id)) {
                $stage->update(['order' => ++$order]);
            }
        }
    }
}
