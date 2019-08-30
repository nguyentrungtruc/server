<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $table = 'ec_sizes';

    protected $fillable = [
        'name', '_name'
    ];

	protected $guarded = [];

	protected $hidden = ['pivot'];

	public function scopeOrderByAsc($query, $column) {
        return $query->orderBy($column, 'asc');
    }

    public function scopeOrderByDesc($query, $column) {
        return $query->orderBy($column, 'desc');
    }
}
