<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingType extends Model
{
	protected $table    = 'ec_rating_types';
	
	protected $fillable = ['name'];
	
	protected $guarded  = [];
	
	protected $hidden   = [];

	public function scopeOrderByAsc($query, $column) {
        return $query->orderBy($column, 'asc');
    }

    public function scopeOrderByDesc($query, $column) {
        return $query->orderBy($column, 'desc');
    }
}
