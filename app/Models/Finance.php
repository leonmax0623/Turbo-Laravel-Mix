<?php

namespace App\Models;

use Database\Factories\FinanceFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Finance
 *
 * @property int $id
 * @property string $name
 * @property int $finance_group_id
 * @property string $operation_type
 * @property int $sum
 * @property int $status
 * @property int $order_id
 * @property string $payment_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static FinanceFactory factory(...$parameters)
 * @method static Builder|Finance newModelQuery()
 * @method static Builder|Finance newQuery()
 * @method static Builder|Finance query()
 * @method static Builder|Finance whereCreatedAt($value)
 * @method static Builder|Finance whereFinanceGroupId($value)
 * @method static Builder|Finance whereId($value)
 * @method static Builder|Finance whereName($value)
 * @method static Builder|Finance whereOperationType($value)
 * @method static Builder|Finance whereSum($value)
 * @method static Builder|Finance whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int|null $department_id
 * @property int|null $totalPaidSum
 * @property int|null $totalPaySum
 * @property-read Order|null $order
 * @property-read AtolLog|null|HasMany $financeLogs
 * @property-read FinanceGroup $financeGroup
 * @property-read Department|null $department
 * @method static Builder|Finance whereDepartmentId($value)
 */
class Finance extends Model
{
    use HasFactory;

    /* PAYMENT_TYPES */
    public const PAYMENT_CASH               = 'cash';
    public const PAYMENT_ELECTRONICALLY     = 'electronically';

    /* TYPES */
    public const OPERATION_SELL           = 'sell';
    public const OPERATION_SELL_RETURN    = 'sellReturn';
    public const OPERATION_BUY            = 'buy';
    public const OPERATION_BUY_RETURN     = 'buyReturn';

    public const PAYMENT_TYPES = [
        Finance::PAYMENT_CASH             => 'Наличными',
        Finance::PAYMENT_ELECTRONICALLY   => 'Безналичными',
    ];

    public const OPERATION_TYPES = [
        Finance::OPERATION_SELL           => 'Чек прихода',
        Finance::OPERATION_SELL_RETURN    => 'Чек возврата прихода',
        Finance::OPERATION_BUY            => 'Чек расхода',
        Finance::OPERATION_BUY_RETURN     => 'Чек возврата расхода',
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'sum'               => 'integer',
        'finance_group_id'  => 'integer',
        'department_id'     => 'integer',
        'order_id'          => 'integer',
    ];

    /* relations */

    public function financeGroup(): BelongsTo
    {
        return $this->belongsTo(FinanceGroup::class);
    }

    public function financeLogs(): MorphMany
    {
        return $this->morphMany(AtolLog::class, 'entity')->orderBy('id', 'desc');
    }

    public function getTotalPaidSumAttribute(): int|null
    {
        $sell = $this->financeLogs
            ->where('operation_type', Finance::OPERATION_SELL)
            ->where('status', 'ready')
            ->sum('sum');

        $sellReturn = $this->financeLogs
            ->where('operation_type', Finance::OPERATION_SELL_RETURN)
            ->where('status', 'ready')
            ->sum('sum');

        return $sell - $sellReturn;
    }

    public function getTotalPaySumAttribute(): int|null
    {
        return $this->order?->id ? $this->order?->totalSum - $this->totalPaidSum : 0;
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
