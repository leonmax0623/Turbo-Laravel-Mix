<?php

namespace App\Models;

use Database\Factories\TaskFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

/**
 * App\Models\Task
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $status
 * @property int $position
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static TaskFactory factory(...$parameters)
 * @method static Builder|Task newModelQuery()
 * @method static Builder|Task newQuery()
 * @method static Builder|Task query()
 * @method static Builder|Task whereCreatedAt($value)
 * @method static Builder|Task whereDescription($value)
 * @method static Builder|Task whereId($value)
 * @method static Builder|Task whereName($value)
 * @method static Builder|Task whereStatus($value)
 * @method static Builder|Task whereUpdatedAt($value)
 * @method static Builder|Task whereUserId($value)
 * @mixin Eloquent
 * @property int|null $author_id
 * @property int|null $department_id
 * @property string|null $start_at
 * @property string|null $end_at
 * @property string|null $deadline_at
 * @property int|null $order_id
 * @property boolean $is_map
 * @property-read Collection|Checkbox[] $checkboxes
 * @property-read int|null $checkboxes_count
 * @method static Builder|Task whereAuthorId($value)
 * @method static Builder|Task whereDeadlineAt($value)
 * @method static Builder|Task whereDepartmentId($value)
 * @method static Builder|Task whereEndAt($value)
 * @method static Builder|Task whereOrderId($value)
 * @method static Builder|Task whereStartAt($value)
 * @property-read User|null $author
 * @property-read Department|null $department
 * @property-read PipelineTask|HasMany $pipelineTasks
 * @property-read Media|MorphMany $files
 * @property-read Order|null $order
 * @property-read User|null $user
 * @property-read MapAnswer|HasOne|null $mapAnswer
 * @property-read TaskMap|BelongsTo|null $taskMap
 * @property-read ModelLog|HasMany|null $taskLogs
 */
class Task extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public const FILES_DISK = 'tasks';

    public const STATUS_WAIT = 'wait';
    public const STATUS_PROCESS = 'process';
    public const STATUS_PAUSE = 'pause';
    public const STATUS_DONE = 'done';

    protected $guarded = ['id'];

    protected $casts = [
        'department_id' => 'integer',
        'author_id' => 'integer',
        'user_id' => 'integer',
        'order_id' => 'integer',
        'start_at' => 'datetime:Y-m-d',
        'end_at' => 'datetime:Y-m-d',
        'deadline_at' => 'datetime:Y-m-d',
        'position' => 'integer',
        'is_map' => 'boolean'
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
            self::STATUS_DONE
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('files');
    }

    /* relations */

    public function checkboxes(): HasMany
    {
        return $this->hasMany(Checkbox::class)->orderBy('id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function files(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function pipelines(): BelongsToMany
    {
        return $this->belongsToMany(Pipeline::class)
            ->withPivot('stage_id', 'created_at', 'updated_at');
    }

    public function pipelineTasks(): HasMany
    {
        return $this->hasMany(PipelineTask::class);
    }

    public function mapAnswer(): HasOne
    {
        return $this->hasOne(MapAnswer::class);
    }

    public function taskMap(): HasOne
    {
        return $this->hasOne(TaskMap::class);
    }

    public function taskLogs(): HasMany
    {
        return $this->hasMany(ModelLog::class, 'model_id')
            ->where('model_type', 'App\Models\Task');
    }

}
