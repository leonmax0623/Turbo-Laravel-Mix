<?php

namespace App\Services\Works;

use App\Models\Work;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class WorkService
{
    /**
     * @param array $filter
     * @return LengthAwarePaginator
     */
    public function getPaginatedWorks(array $filter): LengthAwarePaginator
    {
        $works = Work::with('user', 'order');

        if (!empty($filter['order_id'])) {
            $works->where('order_id', $filter['order_id']);
        }

        return $works->orderBy('id', 'desc')->paginate(30);
    }

    /**
     * @param  array  $data
     * @return Work
     */
    public function store(array $data): Work
    {
        return Work::create($data);
    }

    /**
     * @param  int  $workId
     * @return Work
     */
    public function getWorkById(int $workId): Work
    {
        return Work::findOrFail($workId);
    }

    /**
     * @param  int  $workId
     * @param  array  $data
     * @return Work
     */
    public function update(int $workId, array $data): Work
    {
        $work = $this->getWorkById($workId);
        $work->update($data);

        return $work;
    }

    /**
     * @param  int  $workId
     */
    public function delete(int $workId): void
    {
        Work::where('id', $workId)->delete();
    }

}
