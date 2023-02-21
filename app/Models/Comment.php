<?php

namespace App\Models;

use App\Traits\ModelTypeTrait;
use Database\Factories\CommentFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Comment
 *
 * @property int $id
 * @property string $description
 * @property string $commentable_type
 * @property int $commentable_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $commentable
 * @property-read User $user
 * @property Media[]|null $files
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @method static CommentFactory factory(...$parameters)
 * @method static Builder|Comment newModelQuery()
 * @method static Builder|Comment newQuery()
 * @method static Builder|Comment query()
 * @method static Builder|Comment whereCommentableId($value)
 * @method static Builder|Comment whereCommentableType($value)
 * @method static Builder|Comment whereCreatedAt($value)
 * @method static Builder|Comment whereDescription($value)
 * @method static Builder|Comment whereId($value)
 * @method static Builder|Comment whereUpdatedAt($value)
 * @method static Builder|Comment whereUserId($value)
 * @mixin Eloquent
 */
class Comment extends Model implements HasMedia
{
    use HasFactory, ModelTypeTrait, InteractsWithMedia;

    public const FILES_DISK = 'comments';

    protected $guarded = ['id'];

    /**
     * @return string[]
     */
    public static function getModelMap(): array
    {
        return [
            'task' => 'App\Models\Task',
            'order' => 'App\Models\Order',
        ];
    }

    public static function getTypes(): array
    {
        return [
            'task' => 'task',
            'order' => 'order',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('files')
            ->singleFile();
    }

    /* relations */

    public function files(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function getPhotoUrlAttribute()
    {
        return optional($this->getFirstMedia('photo'))->getFullUrl();
    }

    public function getVideoUrlAttribute()
    {
        return optional($this->getFirstMedia('video'))->getFullUrl();
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
