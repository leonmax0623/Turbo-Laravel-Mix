<?php

namespace App\Services\Orders;

use App\Exceptions\CustomValidationException;
use Illuminate\Support\Collection;
use App\Models\Order;

class OrderService
{
    /**
     * @param array $filter
     * @return Collection
     */
    public function getAll(array $filter): Collection
    {
        $order =  Order::with(
                'user',
                'client',
                'car',
                'department',
                'appealReason',
            );

        if (!empty($filter['department_id'])) {
            $order->where('department_id', $filter['department_id']);
        }

        if (!empty($filter['client_id'])) {
            $order->where('client_id', $filter['client_id']);
        }

        if (!empty($filter['car_id'])) {
            $order->where('car_id', $filter['car_id']);
        }

        if (!empty($filter['created_after'])) {
            $order->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime($filter['created_after'])));
        }

        if (!empty($filter['created_before'])) {
            $order->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($filter['created_before'])));
        }

        return $order->orderByDesc('id')->get();
    }

    /**
     * @param  array  $data
     * @return Order
     */
    public function store(array $data): Order
    {
        return Order::create($data);
    }

    /**
     * @param  int  $orderId
     * @return Order
     */
    public function getOrderById(int $orderId): Order
    {
        return Order::findOrFail($orderId);
    }

    /**
     * @param  int  $orderId
     * @param  array  $data
     * @return Order
     */
    public function update(int $orderId, array $data): Order
    {
        $order = $this->getOrderById($orderId);
        $order->update($data);

        return $order;
    }

    /**
     * @param  int  $orderId
     */
    public function delete(int $orderId): void
    {
        Order::where('id', $orderId)->delete();
    }

    /**
     * @param  Order  $order
     * @param  array  $data
     * @throws CustomValidationException
     */
    public function updateStage(Order $order, array $data): void
    {
        if(data_get($data, 'order_stage_id') !== $order->order_stage_id) {
            $order->update(['order_stage_id' => data_get($data, 'order_stage_id')]);
        }

    }
}
