<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'ec_cities';
    
    protected $fillable = [
        'city_name', 'city_slug', 'lat', 'lng', 'zipcode', 'city_show', 'country_id'
    ];
    
    protected $guarded = [];

    public function scopeOrderByAsc($query, $column) {
        return $query->orderBy($column, 'asc');
    }

    public function scopeOrderByDesc($query, $column) {
        return $query->orderBy($column, 'desc');
    }

    public function getCityShowAttribute($value) {
        if($value) {
            return true;
        } 
        return false;

    }
    
    public function country() {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    public function districts() {
        return $this->hasMany('App\Models\District', 'city_id');
    }

    public function stores() {
        return $this->hasManyThrough('App\Models\Store', 'App\Models\District', 'city_id', 'district_id', 'id', 'id');
    }

    public function deliveries() {
        return $this->belongsToMany('App\Models\Range', 'ec_city_ec_range', 'city_id', 'range_id')->withPivot('price');
    }

    public function service() {
        return $this->hasOne('App\Models\CityService', 'city_id');
    }
}
