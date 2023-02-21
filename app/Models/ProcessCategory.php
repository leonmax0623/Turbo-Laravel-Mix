<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\ProcessCategory
 *
 * @property int $id
 * @property string $name
 * @property int $appeal_reason_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property BelongsToMany|null|ProcessTask[] $processTasks
 * @property BelongsTo|null|AppealReason[] $appealReason
 * @method static OrderFactory factory(...$parameters)
 * @method static Builder|ProcessCategory newModelQuery()
 * @method static Builder|ProcessCategory newQuery()
 * @method static Builder|ProcessCategory query()
 * @mixin Eloquent
 */
class ProcessCategory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = ['appeal_reason_id' => 'integer'];

    /* relations */

    public function appealReason(): BelongsTo
    {
        return $this->belongsTo(AppealReason::class);
    }

    public function processTasks(): BelongsToMany
    {
        return $this->belongsToMany(ProcessTask::class);
    }
}
