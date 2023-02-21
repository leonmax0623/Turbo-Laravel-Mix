<?php

namespace App\Services\Finances;

use App\Exceptions\UsedInOtherTableException;
use App\Models\FinanceGroup;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Throwable;

class FinanceGroupService
{
    /**
     * @return LengthAwarePaginator
     */
    public function getPaginatedFinanceGroups(): LengthAwarePaginator
    {
        return FinanceGroup::orderBy('id', 'desc')->paginate(30);
    }

    /**
     * @param  array  $data
     * @return FinanceGroup
     */
    public function store(array $data): FinanceGroup
    {
        return FinanceGroup::create($data);
    }

    /**
     * @param  int  $financeGroupId
     * @return FinanceGroup
     */
    public function getFinanceGroupById(int $financeGroupId): FinanceGroup
    {
        return FinanceGroup::findOrFail($financeGroupId);
    }

    /**
     * @param  int  $financeGroupId
     * @param  array  $data
     * @return FinanceGroup
     */
    public function update(int $financeGroupId, array $data): FinanceGroup
    {
        $financeGroup = $this->getFinanceGroupById($financeGroupId);
        $financeGroup->update($data);

        return $financeGroup;
    }

    /**
     * @param  int  $financeGroupId
     * @throws UsedInOtherTableException
     */
    public function delete(int $financeGroupId): void
    {
       try {
            FinanceGroup::where('id', $financeGroupId)->delete();
        } catch (Throwable) {
            throw new UsedInOtherTableException(
                'Финансовую группу нельзя удалить, так как она уже используется в других таблицах', 422
            );
        }
    }
}
