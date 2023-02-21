<?php

namespace App\Services\Finances;

use App\Http\Resources\Finances\FinanceResource;
use App\Models\Order;
use App\Services\Atol\AtolService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Exceptions\UsedInOtherTableException;
use App\Models\Finance;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Throwable;

class FinanceService
{
    /**
     * @param array $filter
     * @return LengthAwarePaginator
     */
    public function getPaginatedFinances(array $filter): LengthAwarePaginator
    {
        $finances = Finance::with([
            'financeGroup',
            'financeLogs',
            'department',
            'order',
        ]);

        if (!empty($filter['order'])) {
            if ($filter['order'] === 'name') {
                $finances->orderBy('name');
            } elseif ($filter['order'] === 'sum') {
                $finances->orderBy('sum');
            } elseif ($filter['order'] === 'operation_type') {
                $finances->orderBy('operation_type');
            } elseif ($filter['order'] === 'payment_type') {
                $finances->orderBy('payment_type');
            } elseif ($filter['order'] === 'status') {
                $finances->orderBy('status');
            } elseif ($filter['order'] === 'date') {
                $finances->orderBy('created_at', 'desc');
            } elseif ($filter['order'] === 'department') {
                $finances->orderBy('department_id');
            } elseif ($filter['order'] === 'order_id') {
                $finances->orderBy('order_id');
            } else { // id or other
                $finances->orderBy('id', 'desc');
            }
        } else {
            $finances->orderBy('id', 'desc');
        }

        if (!empty($filter['name'])) {
            $finances->where('name', 'like', '%' . $filter['name'] . '%');
        }

        if (isset($filter['status'])) {
            if( in_array($filter['status'], ['null', 0]) ) {
                $finances->whereNull('status');
            } else {
                $finances->where('status', $filter['status']);
            }
        }

        if (!empty($filter['order_id'])) {
            $finances->where('order_id', $filter['order_id']);
        }

        if (!empty($filter['operation_type'])) {
            $finances->where('operation_type', $filter['operation_type']);
        }

        if (!empty($filter['payment_type'])) {
            $finances->where('payment_type', $filter['payment_type']);
        }

        if (!empty($filter['department_id'])) {
            $finances->where('department_id', $filter['department_id']);
        }

        if (!empty($filter['sum'])) {
            $finances->where('sum', $filter['sum']);
        }

        if (!empty($filter['start_date'])) {
            $date = Carbon::parse($filter['start_date'])->startOfDay()->format('Y-m-d H:i:s');
            $finances->where('created_at', '>=', $date);
        }

        if (!empty($filter['end_date'])) {
            $date = Carbon::parse($filter['end_date'])->endOfDay()->format('Y-m-d H:i:s');
            $finances->where('created_at', '<=', $date);
        }

        return $finances->paginate(30);
    }

    /**
     * @param array $data
     * @return Finance
     */
    public function store(array $data): Finance
    {
        return Finance::create($data);
    }

    /**
     * @param int $financeId
     * @return Finance
     */
    public function getFinanceById(int $financeId): Finance
    {
        return Finance::findOrFail($financeId);
    }

    /**
     * @param int $financeId
     * @param array $data
     * @return Finance
     */
    public function update(int $financeId, array $data): Finance
    {
        $finance = $this->getFinanceById($financeId);

        $finance->update($data);

        return $finance;
    }

    /**
     * @param int $financeId
     * @throws UsedInOtherTableException
     */
    public function delete(int $financeId): void
    {
        try {
            Finance::where('id', $financeId)->delete();
        } catch (Throwable) {
            throw new UsedInOtherTableException(
                'Финансовую операцию нельзя удалить, так как она уже используется в других таблицах', 422
            );
        }
    }

    /**
     * @param int $financeId
     * @return JsonResource
     * @throws UsedInOtherTableException
     */
    public function payment(int $financeId): JsonResource
    {
        return (new AtolService)->payment($financeId, Finance::class, FinanceResource::class);
    }

    /**
     * @throws UsedInOtherTableException
     */
    public function paymentStatus(int $financeId): JsonResource
    {
        return (new AtolService)->paymentStatus($financeId, Finance::class, FinanceResource::class);
    }

    /**
     * @param array $filter
     * @return JsonResponse
     */
    public function summaries(array $filter): JsonResponse
    {

        $expense = Finance::query()->where([
            ['operation_type', '=', Finance::OPERATION_BUY],
            ['status', '=', 'ready']
        ]);

        $expected = Finance::query()->where([
            ['operation_type', '=', Finance::OPERATION_SELL],
            ['status', '!=', 'ready']
        ]);

        $balance = Finance::query()->where([
            ['operation_type', '=', Finance::OPERATION_SELL],
            ['status', '=', 'ready']
        ]);

        if (!empty($filter['start_date'])) {
            $date = Carbon::parse($filter['start_date'])->startOfDay()->format('Y-m-d H:i:s');
            $expense->where('created_at', '>=', $date);
            $expected->where('created_at', '>=', $date);
            $balance->where('created_at', '>=', $date);
        }

        if (!empty($filter['end_date'])) {
            $date = Carbon::parse($filter['end_date'])->endOfDay()->format('Y-m-d H:i:s');
            $expense->where('created_at', '<=', $date);
            $expected->where('created_at', '<=', $date);
            $balance->where('created_at', '<=', $date);
        }

        $balanceTotal = 0;

        foreach ($balance->get() as $item) {
            $balanceTotal += $item->totalPaidSum;
        }

        return response_json([
            'expense'   => $expense->sum('sum'),  // Траты: Расходы со статусом оплачено
            'expected'  => $expected->sum('sum'), // Ожидается: Приходы где ещё не оплачены
            'balance'   => $balanceTotal,                // Баланс: Приходы со статусом оплачено
        ]);
    }
}
