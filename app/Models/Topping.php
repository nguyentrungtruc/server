<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topping extends Model
{
	protected $table = 'ec_toppings';

	protected $fillable = [
        'name', '_name', 'price', 'topping_show', 'store_id', 
    ];

	protected $guard = [];

	public function scopeExist($query, $name, $storeId) {
		return $query->where('name', $name)->where('store_id', $storeId);
	}

	public function scopeIgnore($query, $id) {
		return $query->where('id', '!=', $id);
	}

	public function scopeOrderByAsc($query, $column) {
        return $query->orderBy($column, 'asc');
    }

    public function scopeOrderByDesc($query, $column) {
        return $query->orderBy($column, 'desc');
    }

	public function store() {
		return $this->belongsTo('App\Models\Store', 'store_id');
	}
}
