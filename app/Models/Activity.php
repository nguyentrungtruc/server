<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
	protected $table = 'ec_activities';

	protected $fillable = [
        'daysofweek', 'number'
    ];
    
    protected $guarded = [];

    public function scopeOrderByAsc($query, $column) {
        return $query->orderBy($column, 'asc');
    }

    public function scopeOrderByDesc($query, $column) {
        return $query->orderBy($column, 'desc');
    }

	public function activities() {
		return $this->belongsToMany('App\Models\Store', 'App\Models\ActivityDetails', 'activity_id', 'store_id')->withPivot(['times']);
	}

}
