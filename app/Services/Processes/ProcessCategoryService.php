<?php

namespace App\Services\Processes;

use App\Exceptions\UsedInOtherTableException;
use App\Models\ProcessCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Throwable;

class ProcessCategoryService
{
    /**
     * @param array $filter
     * @return LengthAwarePaginator
     */
    public function getPaginatedProcessCategories(array $filter): LengthAwarePaginator
    {
        $processCategory = ProcessCategory::orderBy('id', 'desc');

        if (data_get($filter, 'appeal_reason_id')) {
            $processCategory->where('appeal_reason_id', data_get($filter, 'appeal_reason_id'));
        }

        return $processCategory->paginate(30);
    }

    /**
     * @param array $data
     * @return ProcessCategory
     */
    public function store(array $data): ProcessCategory
    {
        return ProcessCategory::create($data);
    }

    /**
     * @param int $processCategoryId
     * @return ProcessCategory
     */
    public function getProcessCategoryById(int $processCategoryId): ProcessCategory
    {
        return ProcessCategory::findOrFail($processCategoryId);
    }

    /**
     * @param int $processCategoryId
     * @param array $data
     * @return ProcessCategory
     */
    public function update(int $processCategoryId, array $data): ProcessCategory
    {
        $processCategory = $this->getProcessCategoryById($processCategoryId);
        $processCategory->update($data);

        return $processCategory;
    }

    /**
     * @param int $processCategoryId
     * @throws UsedInOtherTableException
     */
    public function delete(int $processCategoryId): void
    {
        try {
            ProcessCategory::where('id', $processCategoryId)->delete();
        } catch (Throwable) {
            throw new UsedInOtherTableException(
                'Категорию нельзя удалить, так как он уже используется в других таблицах', 422
            );
        }
    }
}
