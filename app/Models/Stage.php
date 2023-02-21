<?php

namespace App\Models;

use Database\Factories\StageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Stage
 *
 * @property int $id
 * @property string $name
 * @property string $color
 * @property int $pipeline_id
 * @property int $order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Pipeline $pipeline
 * @method static Builder|Stage newModelQuery()
 * @method static Builder|Stage newQuery()
 * @method static Builder|Stage query()
 * @method static Builder|Stage whereColor($value)
 * @method static Builder|Stage whereCreatedAt($value)
 * @method static Builder|Stage whereId($value)
 * @method static Builder|Stage whereName($value)
 * @method static Builder|Stage wherePipelineId($value)
 * @method static Builder|Stage whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static StageFactory factory(...$parameters)
 */
class Stage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = ['pipeline_id' => 'integer'];

    /* relations */

    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(Pipeline::class);
    }

    public function tasks(): MorphToMany
    {
        return $this->morphedByMany(Task::class, 'taggable');
    }

}
