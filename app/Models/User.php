<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
//laravel permission
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    // use HasProfilePhoto;
    use Notifiable;
    // use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'knowledge_area_id',
        'institution_id'
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
        'password' => 'hashed',
    ];

    //laravel permission

    public function scopeFiltro($query, array $filtros)
    {
        $query->when($filtros['profile'] ?? null, function ($query, $profile) {
            $query->join('model_has_roles as mhs', 'users.id', '=', 'mhs.model_id');
            $query->where('mhs.role_id', $profile);
        });

        $query->when($filtros['search'] ?? null, function ($query, $search) {
            $tokens = explode(' ', mb_strtoupper($search));
            foreach ($tokens as $token) {
                $query->where(function ($query) use ($token) {
                    $query->where(DB::raw('name'), 'like', '%' . trim($token) . '%')
                        ->orWhere('email', 'like', '%' . trim($token) . '%');
                });
            }
        });

        return $query;
    }
    public function isSuperAdmin(): bool
    {
        return false;
    }

    public function getAllowedViews($module): Collection
    {
        return $this->getAllPermissions()->where('module_key', $module)->pluck('arg');
    }

    public function getRolesArray(): Collection
    {
        return $this->roles()->get()->mapWithKeys(function ($role) {
            return [$role['name'] => true];
        });
    }

    public function getRole(?int $roleId = null, ?string $roleName = null): bool
    {
        if ($roleId) {
            $role = Role::find($roleId);
            if ($role) {
                $roleName = $role->name;
            } else {
                return false;
            }
        }
        return $roleName ? $this->hasRole($roleName) : false;
    }

    public function getPermissionArray(): Collection
    {
        return $this->getAllPermissions()->mapWithKeys(function ($permission) {
            return [$permission['name'] => true];
        });
    }

    public function getPermission(string $permissionName): bool
    { 
        if (Permission::where('name', $permissionName)->exists()) {
            return $this->hasPermissionTo($permissionName);
        }
        return false;
    }

    public function knowledgeArea()
    {
        return $this->belongsTo(KnowledgeArea::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }
}
