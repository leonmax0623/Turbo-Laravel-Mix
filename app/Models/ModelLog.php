<?php

namespace App\Models;

use Database\Factories\TaskFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ModelLog
 *
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property array $data
 * @property string $type
 * @property int $created_by
 * @property Carbon|null $created_at
 * @method static TaskFactory factory(...$parameters)
 * @method static Builder|Task newModelQuery()
 * @method static Builder|Task newQuery()
 * @method static Builder|Task query()
 * @method static Builder|Task whereCreatedAt($value)
 * @method static Builder|Task whereId($value)
 * @mixin Eloquent
 * @property-read User|null $createdBy
 * @property-read Model|null $modelType
 */
class ModelLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $casts = [
        'data' => 'array',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modelType(): BelongsTo
    {
        return $this->belongsTo($this->model_type, 'model_id', 'id');
    }

}
