<?php

namespace App\Models;

use App\Traits\ModelTypeTrait;

use Database\Factories\PipelineFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Pipeline
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property int $department_id
 * @property int $order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Stage[] $stages
 * @property-read Collection|User[] $users
 * @property-read Collection|Task[] $tasks
 * @property-read int|null $stages_count
 * @method static Builder|Pipeline newModelQuery()
 * @method static Builder|Pipeline newQuery()
 * @method static Builder|Pipeline query()
 * @method static Builder|Pipeline whereCreatedAt($value)
 * @method static Builder|Pipeline whereId($value)
 * @method static Builder|Pipeline whereName($value)
 * @method static Builder|Pipeline whereType($value)
 * @method static Builder|Pipeline whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static PipelineFactory factory(...$parameters)
 * @property-read Department|null $department
 */
class Pipeline extends Model
{
    use HasFactory, ModelTypeTrait;

    protected $guarded = ['id'];

    public static function getModelMap(): array
    {
        return [
            'task' => 'App\Models\Task',
        ];
    }

    public static function getTypes(): array
    {
        return [
            'task' => 'task',
        ];
    }


    /* relations */

    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class)->orderBy('id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class)->orderByDesc('id');
    }

    /**
     * @return BelongsTo|Department
     */
    public function department(): BelongsTo|Department
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return BelongsToMany
     */
    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class);
    }
}
