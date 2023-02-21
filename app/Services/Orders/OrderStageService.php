<?php

namespace App\Services\Orders;

use App\Exceptions\CustomValidationException;
use App\Exceptions\UsedInOtherTableException;
use App\Models\Order;
use App\Models\OrderStage;
use Illuminate\Support\Collection;
use Throwable;

class OrderStageService
{
    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return OrderStage::orderBy('order')->get();
    }

    /**
     * @param  array  $data
     * @return OrderStage
     */
    public function store(array $data): OrderStage
    {
        return OrderStage::create($data);
    }

    /**
     * @param  int  $stageId
     * @return OrderStage
     */
    public function getOrderStageById(int $stageId): OrderStage
    {
        return OrderStage::findOrFail($stageId);
    }

    /**
     * @param  int  $stageId
     * @param  array  $data
     * @return OrderStage
     */
    public function update(int $stageId, array $data): OrderStage
    {
        $stage = $this->getOrderStageById($stageId);
        $stage->update($data);

        return $stage;
    }

    /**
     * @param  int  $stageId
     */
    public function delete(int $stageId): void
    {
        try {
            OrderStage::where('id', $stageId)->delete();
        } catch (Throwable) {
            throw new UsedInOtherTableException(
                'Этап нельзя удалить, так как он уже используется в других таблицах', 422
            );
        }
    }

    public function updateOrders(array $ids)
    {
        $stages = OrderStage::whereIn('id', $ids)->get();

        $order = 0;

        foreach ($ids as $id) {
            if ($stage = $stages->firstWhere('id', $id)) {
                $stage->update(['order' => ++$order]);
            }
        }
    }
}
