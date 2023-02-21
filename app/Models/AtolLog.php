<?php

namespace App\Models;

use Database\Factories\FinanceFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\AtolLog
 *
 * @property int $id
 * @property int $entity_id
 * @property string $entity_type
 * @property string $operation_type
 * @property int $sum
 * @property string $status
 * @property array $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read FinanceGroup $financeGroup
 * @method static FinanceFactory factory(...$parameters)
 * @method static Builder|Finance newModelQuery()
 * @method static Builder|Finance newQuery()
 * @method static Builder|Finance query()
 * @method static Builder|Finance whereCreatedAt($value)
 * @method static Builder|Finance whereId($value)
 * @method static Builder|Finance whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Finance|null $finance
 */
class AtolLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'entity_id' => 'integer',
        'data' => 'array',
    ];

    /* relations */

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }
}
