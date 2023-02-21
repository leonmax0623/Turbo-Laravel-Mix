<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $pipeline_id
 * @property int $appeal_reason_id
 * @property int $client_id
 * @property int $user_id
 * @property int $stage_id
 * @property int $department_id
 * @property string $comment
 * @property int $process_category_id
 * @property int $order_stage_id
 * @property int $car_id
 * @property User|BelongsTo $user
 * @property Client|BelongsTo $client
 * @property AppealReason|BelongsTo $appealReason
 * @property Car|BelongsTo $car
 * @property OrderStage|BelongsTo $orderStage
 * @property ProcessCategory|BelongsTo $processCategory
 * @property Department|BelongsTo $department
 * @property ProductRequest|HasMany $productRequests
 * @property Task|HasMany $tasks
 * @property Work|HasMany $works
 * @property Finance|HasMany $finances
 * @property Document|HasMany $documents
 * @property Comment|HasMany $comments
 * @property ProcessTask|BelongsToMany $processTasks
 * @property int|null $totalSum
 * @property int|null $paymentSum
 * @property int|null $paymentSumReturn
 * @property int|null $totalPaidSum
 * @property int|null $totalPaySum
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static OrderFactory factory(...$parameters)
 * @method static Builder|Order newModelQuery()
 * @method static Builder|Order newQuery()
 * @method static Builder|Order query()
 * @method static Builder|Order whereId($value)
 * @method static Builder|Order whereName($value)
 * @mixin Eloquent
 */
class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'user_id' => 'integer',
        'client_id' => 'integer',
        'appeal_reason_id' => 'integer',
        'car_id' => 'integer',
        'department_id' => 'integer',
        'order_stage_id' => 'integer',
        'process_category_id' => 'integer',
    ];

    /* relations */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function appealReason(): BelongsTo
    {
        return $this->belongsTo(AppealReason::class);
    }

    public function orderStage(): BelongsTo
    {
        return $this->belongsTo(OrderStage::class);
    }

    public function processCategory(): BelongsTo
    {
        return $this->belongsTo(ProcessCategory::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function productRequests(): HasMany
    {
        return $this->hasMany(ProductRequest::class);
    }

    public function works(): HasMany
    {
        return $this->hasMany(Work::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function processTasks(): BelongsToMany
    {
        return $this->belongsToMany(ProcessTask::class);
    }

    public function getTotalSumAttribute(): int|null
    {
        $outputSum = 0;

        foreach ($this->productRequests as $productRequest) {
            /** @var ProductRequest $productRequest */
            $outputSum += $productRequest->totalSum;
        }

        return $this->works->sum('sum') + $outputSum;
    }

    public function getPaymentSumAttribute(): int|null
    {
        $total = 0;
        $finances = $this->finances
            ->where('operation_type', Finance::OPERATION_SELL)
            ->where('status', 'ready');

        foreach ($finances as $finance) {
            /** @var Finance $finance */
            $total += $finance->totalPaidSum;
        }

        return $total;
    }

    public function getPaymentSumReturnAttribute(): int|null
    {
        $total = 0;
        $finances = $this->finances
            ->where('operation_type', Finance::OPERATION_SELL_RETURN)
            ->where('status', 'ready');

        foreach ($finances as $finance) {
            /** @var Finance $finance */
            $total += $finance->totalPaidSum;
        }

        return $total;
    }

    public function getTotalPaidSumAttribute(): int|null
    {
        return $this->paymentSum - $this->paymentSumReturn;
    }

    public function getTotalPaySumAttribute(): int|null
    {
        return $this->totalSum - $this->totalPaidSum;
    }

    public function finances(): HasMany
    {
        return $this->hasMany(Finance::class);
    }
}
