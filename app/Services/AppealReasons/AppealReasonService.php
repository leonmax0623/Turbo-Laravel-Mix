<?php

namespace App\Services\AppealReasons;

use App\Exceptions\UsedInOtherTableException;
use App\Models\AppealReason;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Throwable;

class AppealReasonService
{
    /**
     * @return LengthAwarePaginator
     */
    public function getPaginatedAppealReasons(): LengthAwarePaginator
    {
        return AppealReason::orderBy('id', 'desc')->paginate(30);
    }

    /**
     * @param  array  $data
     * @return AppealReason
     */
    public function store(array $data): AppealReason
    {
        return AppealReason::create($data);
    }

    /**
     * @param  int  $appealReasonId
     * @return AppealReason
     */
    public function getAppealReasonById(int $appealReasonId): AppealReason
    {
        return AppealReason::findOrFail($appealReasonId);
    }

    /**
     * @param  int  $appealReasonId
     * @param  array  $data
     * @return AppealReason
     */
    public function update(int $appealReasonId, array $data): AppealReason
    {
        $appealReason = $this->getAppealReasonById($appealReasonId);
        $appealReason->update($data);

        return $appealReason;
    }

    /**
     * @param  int  $appealReasonId
     * @throws UsedInOtherTableException
     */
    public function delete(int $appealReasonId): void
    {
        try {
            AppealReason::where('id', $appealReasonId)->delete();
        } catch (Throwable) {
            throw new UsedInOtherTableException(
                'Причину обращения нельзя удалить, так как она уже используется в других таблицах', 422
            );
        }
    }
}
