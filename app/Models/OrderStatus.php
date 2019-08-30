<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
	protected $table = 'ec_order_status';

	protected $fillable = [
        'order_status_name', 'order_status_description', 'number_order', 'color'
    ];
    
    protected $guarded = [];

    public function scopeOrderByAsc($query, $column) {
        return $query->orderBy($column, 'asc');
    }

    public function scopeOrderByDesc($query, $column) {
        return $query->orderBy($column, 'desc');
    }

	public function orders() {
		return $this->hasMany('App\Models\RegularOrder', 'order_id');
	}
}
