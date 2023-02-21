<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Document
 *
 * @property int $id
 * @property string $name
 * @property string $template
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|DocumentTemplate newModelQuery()
 * @method static Builder|DocumentTemplate newQuery()
 * @method static Builder|DocumentTemplate query()
 * @method static Builder|DocumentTemplate whereCreatedAt($value)
 * @method static Builder|DocumentTemplate whereId($value)
 * @method static Builder|DocumentTemplate whereUpdatedAt($value)
 * @mixin Eloquent
 */
class DocumentTemplate extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
