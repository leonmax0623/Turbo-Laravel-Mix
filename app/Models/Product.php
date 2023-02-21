<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name
 * @property string|null $place
 * @property int $count
 * @property int|null $input_sum
 * @property int|null $output_sum
 * @property string|null $description
 * @property string|null $photo
 * @property Media|null $file
 * @property string|null $sku артикул
 * @property int|null $producer_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Producer|null $producer
 * @property-read Storage|null $storage
 * @property-read User|null $user
 * @property-read ProductRequest[]|HasMany|null $productRequests
 * @method static ProductFactory factory(...$parameters)
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product query()
 * @method static Builder|Product whereCount($value)
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereDescription($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereInputSum($value)
 * @method static Builder|Product whereName($value)
 * @method static Builder|Product whereOutputSum($value)
 * @method static Builder|Product wherePhoto($value)
 * @method static Builder|Product wherePlace($value)
 * @method static Builder|Product whereProducerId($value)
 * @method static Builder|Product whereSku($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read mixed $photo_url
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 */
class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public const PHOTO_DISK = 'products';

    protected $guarded = ['id'];

    protected $casts = [
        'count' => 'integer',
        'input_sum' => 'integer',
        'output_sum' => 'integer',
        'producer_id' => 'integer'
    ];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('photo')
            ->singleFile();
    }

    /* relations */

    public function file(): MorphOne
    {
        return $this->morphOne(Media::class, 'model');
    }

    public function getPhotoUrlAttribute()
    {
        return optional($this->getFirstMedia('photo'))->getFullUrl();
    }

    public function producer(): BelongsTo
    {
        return $this->belongsTo(Producer::class);
    }

    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function productRequests(): HasMany
    {
        return $this->hasMany(ProductRequest::class);
    }
}
