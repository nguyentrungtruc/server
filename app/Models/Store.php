<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table   = "ec_stores";

    protected $fillable = ['store_name', 'store_slug', 'store_phone', 'preparetime', 'store_address', 'lat', 'lng', 'discount', 'store_avatar', 'verified', 'store_show', 'priority', 'user_id', 'district_id', 'type_id', 'status_id', 'views', 'likes'];
    
    protected $guarded = [];
    
    protected $hidden  = ['pivot', 'city_id'];

    // GET STORE OF CITY
    public function scopeOfCity($query, $city_id) {
        $cityId = $city_id;
        if($cityId > 0) {
            return $query->whereHas('district', function($query) use ($cityId) {
                $query->byCityId($cityId);
            });   
        }
        return $query;
    }

    // GET STORE BY TYPE ID
    public function scopeByTypeId($query, $type_id) {
        $typeId = (int) $type_id;
        if($typeId>0) {
            return $query->where('type_id', $typeId);
        }   
        return $query;
    }

    // GET STORE SHOWED
    public function scopeShow($query, $is_show) {
        if(!is_null($is_show)) {
            if($is_show === 'true') {
                return $query->where('store_show', 1);  
            } else {
                return $query->where('store_show', 0);  
            }               
        }  
        return $query;
    }

    // SEARCH STORE BY PLACE
    public function scopeLikePlace($query, $keywords) {
        if(!is_null($keywords)) {
            return $query->where('store_name', 'like',  '%'.$keywords.'%')->orWhere('id', 'like', '%'.$keywords.'%')->orWhere('store_address', 'like', '%'.$keywords.'%');
        }
        return $query;
    }

    public function scopeOrderByDesc($query, $column) {
        return $query->orderBy($column, 'desc');
    }

    public function scopeOrderByAsc($query, $column) {
        return $query->orderBy($column, 'asc');
    }

    public function getVerifiedAttribute($value) {
        if($value) {
            return true;
        } 
        return false;

    }

    public function getStoreShowAttribute($value) {
        if($value) {
            return true;
        } 
        return false;

    }

    public function user() {
    	return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function district() {
    	return $this->belongsTo('App\Models\District', 'district_id');
    }

    public function type() {
    	return $this->belongsTo('App\Models\Type', 'type_id');
    }

    public function status() {
    	return $this->belongsTo('App\Models\StoreStatus', 'status_id');
    }

    public function activities() {
        return $this->belongsToMany('App\Models\Activity', 'ec_activity_ec_store', 'store_id', 'activity_id')->withPivot('times');
    }

    public function catalogues() {
        return $this->hasMany('App\Models\Catalogue', 'store_id');
    }

    public function products() {
        return $this->hasManyThrough('App\Models\Product','App\Models\Catalogue','store_id','catalogue_id');
    }

    public function coupons() {
        return $this->belongsToMany('App\Models\Coupon', 'ec_coupon_ec_store', 'store_id', 'coupon_id');
    }

    public function regularOrders() {
        return $this->hasMany('App\Models\RegularOrder', 'store_id');
    }

    public function toppings() {
        return $this->hasMany('App\Models\Topping', 'store_id');
    }

    public function comments()
    {
        return $this->morphMany('App\Models\StoreComment', 'commentable');
    }
}
