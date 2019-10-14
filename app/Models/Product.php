<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	protected $table = 'ec_products';

	protected $fillable = [
        'name', '_name', 'count', 'have_topping', 'have_size', 'image', 'priority', 'status_id', 'catalogue_id', 'description'
    ];

	protected $guarded = [];

	protected $hidden = [];
	//FIND PRODUCT EXIST
	public function scopeExist($query, $name, $catalogueId) {
		return $query->where('name', $name)->where('catalogue_id', $catalogueId);
	}
	//FIND IGNORE ID
	public function scopeIgnore($query, $id) {
		return $query->where('id', '!=', $id);
	}
	// SORT ASC
	public function scopeOrderByAsc($query, $column) {
        return $query->orderBy($column, 'asc');
    }
    // DESC ASC
    public function scopeOrderByDesc($query, $column) {
        return $query->orderBy($column, 'desc');
    }

    public function getHaveToppingAttribute($value) {
		if($value) {
			return true;
		} 
		return false;

	}

    public function sizes() {
		return $this->belongsToMany('App\Models\Size','ec_product_ec_size','product_id', 'size_id')->withPivot(['price']);
	}

	public function catalogue() {
		return $this->belongsTo('App\Models\Catalogue', 'catalogue_id');
	}    

	public function status() {
		return $this->belongsTo('App\Models\ProductStatus', 'status_id');
	}



	
}
