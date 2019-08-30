<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
	protected $table = 'ec_districts';

	protected $fillable = [
        'district_name', 'district_slug', 'lat', 'lng', 'district_show', 'city_id'
    ];

	protected $guarded = [];

	public function scopeByCityId($query, $cityId) {
		return $query->where('city_id', $cityId);
	}

	public function scopeOrderByAsc($query, $column) {
        return $query->orderBy($column, 'asc');
    }

    public function scopeOrderByDesc($query, $column) {
        return $query->orderBy($column, 'desc');
    }

	public function getDistrictShowAttribute($value) {
		if($value) {
			return true;
		} 
		return false;

	}
	public function city() {
		return $this->belongsTo('App\Models\City', 'city_id');
	}

	public function stores() {
		return $this->hasMany('App\Models\Store', 'district_id');
	}
}
