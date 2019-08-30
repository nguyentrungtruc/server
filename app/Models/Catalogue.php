<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalogue extends Model
{
	protected $table = 'ec_catalogues';

	protected $fillable = [
        'catalogue', '_catalogue', 'priority', 'slug', 'catalogue_show', 'store_id',
    ];

	protected $guarded = [];

	protected $hidden = [];
	//FIND CATALOGUE EXIST
	public function scopeExist($query, $name, $storeId) {
		return $query->where('catalogue', $name)->where('store_id', $storeId);
	}
	//FIND IGNORE ID
	public function scopeIgnore($query, $id) {
		return $query->where('id', '!=', $id);
	}
	//SORT ASC
	public function scopeOrderByAsc($query, $column) {
        return $query->orderBy($column, 'asc');
    }
    //SORT DESC
    public function scopeOrderByDesc($query, $column) {
        return $query->orderBy($column, 'desc');
    }
    
	public function store() {
		return $this->belongsTo('App\Models\Store', 'store_id');
	}

	public function products() {
		return $this->hasMany('App\Models\Product', 'catalogue_id');
	}
}
