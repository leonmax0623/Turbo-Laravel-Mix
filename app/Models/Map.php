<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Map
 *
 * @property int $id
 * @property string $title
 * @property array $data
 * @property Task|BelongsToMany $tasks
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */

class Map extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'data'  => 'array',
        'title' => 'string',
    ];

    public const types = [
        'checkbox' => 'checkbox',
        'text'     => 'text',
        'notes'    => 'notes',
    ];

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_map');
    }
}
