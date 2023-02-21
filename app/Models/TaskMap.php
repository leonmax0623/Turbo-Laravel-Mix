<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TaskMap
 *
 * @property int $id
 * @property int $task_id
 * @property int $map_id
 * @property Map $map
 * @property Task $task
 */
class TaskMap extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'task_map';

    public $timestamps = false;

    protected $casts = [
        'task_id'   => 'integer',
        'map_id'    => 'integer',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class);
    }

}
