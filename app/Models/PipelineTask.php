<?php

namespace App\Models;

use Database\Factories\StageFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\PipelineTask
 *
 * @property int $id
 * @property string $task_id
 * @property string $stage_id
 * @property int $pipeline_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Pipeline $pipeline
 * @property-read Stage $stage
 * @property-read Task $task
 * @method static Builder|Stage newModelQuery()
 * @method static Builder|Stage newQuery()
 * @method static Builder|Stage query()
 * @method static Builder|Stage whereCreatedAt($value)
 * @method static Builder|Stage whereId($value)
 * @method static Builder|Stage wherePipelineId($value)
 * @method static Builder|Stage whereStageId($value)
 * @method static Builder|Stage whereTaskId($value)
 * @method static Builder|Stage whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static StageFactory factory(...$parameters)
 */
class PipelineTask extends Model
{
    use HasFactory;

    protected $table = 'pipeline_task';

    protected $guarded = ['id'];

    protected $casts = [
        'pipeline_id' => 'integer',
        'stage_id' => 'integer',
        'task_id' => 'integer'
    ];

    /* relations */

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(Pipeline::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
