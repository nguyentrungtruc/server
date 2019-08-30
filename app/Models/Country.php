<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
	protected $table = 'ec_countries';	
	protected $fillable = ['country_name', 'lang', 'country_slug', 'lat', 'lng', 'dialingcode', 'country_show'];
	protected $guarded = [];

	public function getCountryShowAttribute($value) {
		if($value) {
			return true;
		} 
		return false;

	}

	public function scopeOrderByAsc($query, $column) {
		return $query->orderBy($column, 'asc');
	}

	public function scopeOrderByDesc($query, $column) {
		return $query->orderBy($column, 'desc');
	}

	public function cities () {
		return $this->hasMany('App\Models\City', 'country_id');
	}

}
