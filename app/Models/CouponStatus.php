<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponStatus extends Model
{
	protected $table = 'ec_coupon_status';

	protected $fillable = [
        'coupon_status_name', 'coupon_status_description', 'color'
    ];
    
    protected $guarded = [];

    public function scopeOrderByAsc($query, $column) {
        return $query->orderBy($column, 'asc');
    }

    public function scopeOrderByDesc($query, $column) {
        return $query->orderBy($column, 'desc');
    }

	public function coupons() {
		return $this->hasMany('App\Models\Coupon', 'status_id');
	}
}
