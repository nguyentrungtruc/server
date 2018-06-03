<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topping extends Model
{
	protected $table = 'ec_toppings';

	protected $guard = [];

	public function store() {
		return $this->belongsTo('App\Models\Store', 'store_id');
	}
}
