<?php

namespace App\Services\Products;

use App\Exceptions\UsedInOtherTableException;
use App\Models\Department;
use App\Models\Storage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class StorageService
{
    /**
     * @param Department $department
     * @return LengthAwarePaginator
     */
    public function getAll(Department $department): LengthAwarePaginator
    {
        return Storage::query()->orderBy('id', 'desc')->where('department_id', $department->id)->paginate(30);
    }

    /**
     * @param array $data
     * @return Storage
     * @throws UsedInOtherTableException
     */
    public function store(array $data): Storage
    {
        return Storage::create($data);
    }

    /**
     * @param  int  $storageId
     * @return Storage
     */
    public function getStorageById(int $storageId): Storage
    {
        return Storage::findOrFail($storageId);
    }

    /**
     * @param int $storageId
     * @param array $data
     * @return Storage
     */
    public function update(int $storageId, array $data): Storage
    {

        $storage = $this->getStorageById($storageId);

        $storage->update($data);

        return $storage;
    }

    /**
     * @param  int  $storageId
     * @throws UsedInOtherTableException
     */
    public function delete(int $storageId): void
    {
        try {
            Storage::where('id', $storageId)->delete();
        } catch (Throwable) {
            throw new UsedInOtherTableException(
                'Склад нельзя удалить, так как он уже используется в других таблицах', 422
            );
        }
    }
}
