<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreStatus extends Model
{
    protected $table = 'ec_store_status';

    protected $fillable = [
        'store_status_name', 'store_status_description', 'color'
    ];
    
    protected $guarded = [];

    public function scopeOrderByAsc($query, $column) {
        return $query->orderBy($column, 'asc');
    }

    public function scopeOrderByDesc($query, $column) {
        return $query->orderBy($column, 'desc');
    }

    public function stores() {
    	return $this->hasMany('App\Models\Store', 'status_id');
    }
}
