<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $guard_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection|User[] $users
 * @property-read int|null $users_count
 * @method static Builder|Role newModelQuery()
 * @method static Builder|Role newQuery()
 * @method static Builder|Role notAdmin()
 * @method static Builder|Role permission($permissions)
 * @method static Builder|Role query()
 * @method static Builder|Role whereCreatedAt($value)
 * @method static Builder|Role whereGuardName($value)
 * @method static Builder|Role whereId($value)
 * @method static Builder|Role whereName($value)
 * @method static Builder|Role whereTitle($value)
 * @method static Builder|Role whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read mixed $is_admin
 */
class Role extends SpatieRole
{
    /**
     * @param  string  $title
     * @param  int|null  $exceptId
     * @return string
     */
    public static function genName(string $title, ?int $exceptId = null): string
    {
        $initName = Str::lower(Str::slug($title));

        $name = $initName;
        $count = 1;

        while (Role::where('name', $name)->where('id', '<>', $exceptId)->exists()) {
            $count++;
            $name = $initName.'-'.$count;
        }

        return $name;
    }

    /* attributes */

    public function getIsAdminAttribute()
    {
        return $this->name === RoleConst::ROLE_ADMIN;
    }

    /* scopes */

    public function scopeNotAdmin($query)
    {
        $query->where('name', '<>', RoleConst::ROLE_ADMIN);
    }
}
