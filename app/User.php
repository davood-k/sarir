<?php

namespace App;

use App\Permission;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, HasRole;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_superuser',
        'is_staff'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function issuperuser()
    {
        return $this->is_superuser;
    }

    public function isstaff()
    {
        return $this->is_staff;
    }

    public function definations()
    {
        return $this->hasMany(Defination::class);
    }
    public function khademyars()
    {
        return $this->hasMany(Khademyar::class);
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
    public function hasRole($roles)
    {
        return !!$roles->intersect($this->roles)->all();
    }
    public function hasPermission($permission)
    {
        return $this->permissions->contains('name', $permission->name) || $this->hasRole($permission->roles);
    }
}