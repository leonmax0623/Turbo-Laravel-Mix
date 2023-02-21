<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\ProductRequest
 *
 * @property int $id
 * @property int $count
 * @property Carbon|null $date_issue
 * @property int $user_id
 * @property int $product_id
 * @property int|null $order_id
 * @property int|null $sum
 * @property int|null $totalSum
 * @property string $status
 * @property User|null $user
 * @property Order $order
 * @property Product $product
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Checkbox[] $checkboxes
 * @property-read int|null $checkboxes_count
 * @method static Builder|ProductRequest newModelQuery()
 * @method static Builder|ProductRequest newQuery()
 * @method static Builder|ProductRequest query()
 * @method static Builder|ProductRequest whereCreatedAt($value)
 * @method static Builder|ProductRequest whereId($value)
 * @method static Builder|ProductRequest whereOrderId($value)
 * @method static Builder|ProductRequest whereProductId($value)
 * @method static Builder|ProductRequest whereStatus($value)
 * @method static Builder|ProductRequest whereCount($value)
 * @method static Builder|ProductRequest whereUpdatedAt($value)
 * @method static Builder|ProductRequest whereUserId($value)
 * @mixin Eloquent
 */
class ProductRequest extends Model
{
    use HasFactory;

    public const STATUS_WAIT = 'wait';
    public const STATUS_PROCESS = 'process';
    public const STATUS_PAUSE = 'pause';
    public const STATUS_DONE = 'done';
    public const STATUS_CANCEL = 'cancel';

    protected $guarded = ['id'];

    protected $casts = [
        'user_id'       => 'integer',
        'product_id'    => 'integer',
        'order_id'      => 'integer',
        'count'         => 'integer',
        'sum'           => 'integer',
    ];

    /**
     * @return string[]
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_WAIT,
            self::STATUS_PROCESS,
            self::STATUS_PAUSE,
            self::STATUS_DONE,
            self::STATUS_CANCEL,
        ];
    }

    public function getTotalSumAttribute(): int|null
    {
        return $this->sum * $this->count;
    }
    /* relations */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

}
