<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Work
 *
 * @property int $id
 * @property int $sum
 * @property string $name
 * @property string $comments
 * @property Carbon|null $time
 * @property int $user_id
 * @property int $order_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $user
 * @property-read Order|null $order
 * @method static Builder|ProductRequest newModelQuery()
 * @method static Builder|ProductRequest newQuery()
 * @method static Builder|ProductRequest query()
 * @method static Builder|ProductRequest whereCreatedAt($value)
 * @method static Builder|ProductRequest whereId($value)
 * @method static Builder|ProductRequest whereOrderId($value)
 * @method static Builder|ProductRequest whereSum($value)
 * @method static Builder|ProductRequest whereTime($value)
 * @method static Builder|ProductRequest whereUpdatedAt($value)
 * @method static Builder|ProductRequest whereUserId($value)
 * @mixin Eloquent
 */

class Work extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'comments',
        'sum',
        'time',
        'user_id',
        'order_id'
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'sum' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
