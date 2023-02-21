<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User
 *
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static UserFactory factory(...$parameters)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @mixin Eloquent
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string|null $middle_name
 * @property string|null $phone
 * @property Carbon|null $born_at
 * @property string|null $office_position
 * @property string|null $about
 * @property bool $is_active
 * @property bool $is_about_visible
 * @property bool $is_born_at_visible
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property Carbon|null $login_at
 * @property string|null $avatar
 * @property int|null $department_id
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Department|null $department
 * @property-read Collection|Permission[] $permissions
 * @property-read Collection|Comment[] $comments
 * @property-read int|null $permissions_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read string|null $inn
 * @method static Builder|User active()
 * @method static Builder|User permission($permissions)
 * @method static Builder|User role($roles, $guard = null)
 * @method static Builder|User whereAbout($value)
 * @method static Builder|User whereAvatar($value)
 * @method static Builder|User whereBornAt($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereDepartmentId($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereIsAboutVisible($value)
 * @method static Builder|User whereIsActive($value)
 * @method static Builder|User whereIsBornAtVisible($value)
 * @method static Builder|User whereLoginAt($value)
 * @method static Builder|User whereMiddleName($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User whereOfficePosition($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePhone($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereSurname($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @property-read mixed $avatar_url
 * @method static Builder|User notActive()
 * @property-read mixed $is_admin
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read boolean $IsNotAdmin
 */
class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia;

    public const AVATAR_DISK = 'avatars';

    protected $guard_name = 'sanctum';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'middle_name',
        'phone',
        'is_active',
        'born_at',
        'login_at',
        'is_about_visible',
        'is_born_at_visible',
        'about',
        'email',
        'password',
        'office_position',
        'department_id',
        'inn',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'born_at' => 'date',
        'login_at' => 'datetime',
        'is_about_visible' => 'boolean',
        'is_born_at_visible' => 'boolean',
        'is_active' => 'boolean',
        'department_id' => 'integer',
    ];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('avatar')
            ->singleFile();
    }

    /* attributes */

    public function getAvatarAttribute()
    {
        return $this->getFirstMedia('avatar');
    }

    public function getAvatarUrlAttribute()
    {
        return optional($this->getFirstMedia('avatar'))->getFullUrl();
    }

    public function getIsAdminAttribute()
    {
        return $this->hasRole(RoleConst::ROLE_ADMIN);
    }

    /* scopes */

    public function scopeActive($query)
    {
        $query->where('is_active', 1);
    }

    public function scopeNotActive($query)
    {
        $query->where('is_active', 0);
    }

    /* relations */

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
