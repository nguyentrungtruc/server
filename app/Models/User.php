<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cartalyst\Sentinel\Users\EloquentUser;
use Tymon\JWTAuth\Contracts\JWTSubject as AuthenticatableUserContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Authenticatable implements AuthenticatableUserContract, AuthenticatableContract
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table    = 'ec_users';
    protected $fillable = ['name', 'email', 'password', 'phone', 'birthday', 'gender', 'role_id', 'actived', 'banned', 'free_ship', 'image', 'address', 'lat', 'lng', 'point'];
    protected $hidden   = ['password', 'remember_token', 'api_token'];

    public function scopeOrderByAsc($query, $column) {
        return $query->orderBy($column, 'asc');
    }

    public function scopeOrderByDesc($query, $column) {
        return $query->orderBy($column, 'desc');
    }

    // GET USER ACTIVE
    public function scopeActive($query, $is_active) {
        if(!is_null($is_active)) {
            if($is_active === 'true') {
                return $query->where('actived', 1);  
            } else {
                return $query->where('actived', 0);  
            }               
        }  
        return $query;
    }

    // GET USER BAN
    public function scopeBan($query, $is_ban) {
        if(!is_null($is_ban)) {
            if($is_ban === 'true') {
                return $query->where('banned', 1);  
            } else {
                return $query->where('banned', 0);  
            }               
        }  
        return $query;
    }

    // SEARCH USER
    public function scopeLike($query, $keywords) {
        if(!is_null($keywords)) {
            return $query->where('name', 'like',  '%'.$keywords.'%')->orWhere('phone', 'like', '%'.$keywords.'%')->orWhere('email', 'like', '%'.$keywords.'%')->orWhere('id', 'like', '%'.$keywords.'%');
        }
        return $query;
    }

    public function getGenderAttribute($value) {
        if($value) {
            return true;
        } 
        return false;

    }

    public function getActivedAttribute($value) {
        if($value) {
            return true;
        } 
        return false;

    }

    public function getBannedAttribute($value) {
        if($value) {
            return true;
        } 
        return false;

    }

    // GET USER BY ROLE
    public function scopeByRoleId($query, $roleId) {
        return $query->where('role_id', $roleId);
    }

    public function store() {
        return $this->hasOne('App\Models\Store', 'user_id');
    }

    public function role() {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }
    
    public function active() {
        return $this->hasOne('App\Models\Activation', 'user_id');
    }

    public function hasAnyRole($roles) {
        if(is_array($roles)) {
            foreach($roles as $role) {
                if($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if($this->hasRole($roles)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole($role) {
        if($this->role()->where('name', '=', $role)->first()) {
            return true;
        }
        return false;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function orders() {
        return $this->hasMany('App\Models\RegularOrder', 'user_id');
    }
    public function receivesBroadcastNotificationsOn()
    {
        return 'users.'.$this->id;
    }
}
