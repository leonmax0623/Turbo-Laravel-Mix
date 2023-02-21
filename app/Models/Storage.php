<?php

namespace App\Models;

use Database\Factories\StorageFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Storage
 *
 * @property int $id
 * @property string $name
 * @property int $department_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Department $department
 * @method static StorageFactory factory(...$parameters)
 * @method static Builder|Storage newModelQuery()
 * @method static Builder|Storage newQuery()
 * @method static Builder|Storage query()
 * @method static Builder|Storage whereDepartmentId($value)
 * @method static Builder|Storage whereCreatedAt($value)
 * @method static Builder|Storage whereId($value)
 * @method static Builder|Storage whereName($value)
 * @method static Builder|Storage whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Storage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'department_id' => 'integer'
    ];

    /* relations */

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
