<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Document
 *
 * @property int $id
 * @property string $name
 * @property string $comments
 * @property int $document_template_id
 * @property int $order_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read DocumentTemplate|null $documentTemplate
 * @property-read Order|null $order
 * @method static Builder|Document newModelQuery()
 * @method static Builder|Document newQuery()
 * @method static Builder|Document query()
 * @method static Builder|Document whereCreatedAt($value)
 * @method static Builder|Document whereId($value)
 * @method static Builder|Document whereOrderId($value)
 * @method static Builder|Document whereSum($value)
 * @method static Builder|Document whereTime($value)
 * @method static Builder|Document whereUpdatedAt($value)
 * @method static Builder|Document whereUserId($value)
 * @mixin Eloquent
 */

class Document extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function documentTemplate(): BelongsTo
    {
        return $this->belongsTo(DocumentTemplate::class);
    }
}
