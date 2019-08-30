<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
	protected $table = 'ec_types';

	protected $fillable = [
        'type_name', 'type_slug', 'code', 'type_icon', 'type_show'
    ];

	protected $guarded = [];

	public function scopeOrderByAsc($query, $column) {
        return $query->orderBy($column, 'asc');
    }

    public function scopeOrderByDesc($query, $column) {
        return $query->orderBy($column, 'desc');
    }

	public function getTypeShowAttribute($value) {
		if($value) {
			return true;
		} 
		return false;
	}

	public function stores() {
		return $this->hasMany('App\Models\Store', 'type_id');
	}

}
