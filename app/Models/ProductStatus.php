<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStatus extends Model
{
	protected $table = 'ec_product_status';

	protected $fillable = [
        'product_status_name', 'product_status_description', 'color'
    ];
    
    protected $guarded = [];

    public function scopeOrderByAsc($query, $column) {
        return $query->orderBy($column, 'asc');
    }

    public function scopeOrderByDesc($query, $column) {
        return $query->orderBy($column, 'desc');
    }
	
}
