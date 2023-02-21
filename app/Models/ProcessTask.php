<?php

namespace App\Models;

use Database\Factories\ProducerFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
/**
 * App\Models\ProcessTask
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $position
 * @property int $is_map
 * @property int $order_stage_id
 * @property int $role_id
 * @property int $map_id
 * @property Carbon|null $time
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read $processCheckboxes
 * @property-read $processCategories
 * @property-read $role
 * @property-read $files
 * @property-read $pipelines
 * @property-read $pipelineProcessTasks
 * @property-read $orderProcessTasks
 * @property-read $stages
 * @property-read $orderStage
 * @property-read $map
 * @method static ProducerFactory factory(...$parameters)
 * @method static Builder|Producer newModelQuery()
 * @method static Builder|Producer newQuery()
 * @method static Builder|Producer query()
 * @method static Builder|Producer whereCreatedAt($value)
 * @method static Builder|Producer whereId($value)
 * @method static Builder|Producer whereName($value)
 * @method static Builder|Producer whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ProcessTask extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public const FILES_DISK = 'process_tasks';

    public const PIPELINE_STATUS_MOVED = 'moved';
    public const PIPELINE_STATUS_CLOSED = 'closed';

    protected $guarded = ['id'];

    protected $casts = [
        'process_category_id' => 'integer',
        'role_id' => 'integer',
        'time' => 'integer',
        'position' => 'integer',
        'is_map' => 'boolean',
        'map_id' => 'integer',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('files');
    }

    /* relations */

    /**
     * @return HasMany
     */
    public function processCheckboxes(): HasMany
    {
        return $this->hasMany(ProcessCheckbox::class)->orderBy('id');
    }

    public function processCategories(): BelongsToMany
    {
        return $this->belongsToMany(ProcessCategory::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function files(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function pipelines(): BelongsToMany
    {
        return $this->belongsToMany(Pipeline::class, 'pipeline_process_task')
            ->withPivot('stage_id', 'status', 'created_at', 'updated_at');
    }

    public function pipelineProcessTasks(): HasMany
    {
        return $this->hasMany(PipelineProcessTask::class);
    }

    public function orderProcessTasks(): HasMany
    {
        return $this->hasMany(OrderProcessTask::class);
    }

    public function stages(): BelongsToMany
    {
        return $this->belongsToMany(Stage::class, 'pipeline_process_task');
    }

    public function orderStage(): BelongsTo
    {
        return $this->belongsTo(OrderStage::class);
    }

    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class);
    }
}
