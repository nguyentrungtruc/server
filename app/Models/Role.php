<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	protected $table = 'ec_roles';
	protected $fillable = ['name', 'description'];
	protected $guarded = [];
	
	const ADMIN    = 'Admin';
	const CUSTOMER = 'Customer';	
	const EMPLOYEE = 'Employee';  
	const PARTNER  = 'Partner';
	const SHIPPER  = 'Shipper';

	public function scopeAdmin($query) {
		return $query->where('name', self::ADMIN)->first();
	}

	public function scopeCustomer($query) {
		return $query->where('name', self::CUSTOMER)->first();
	}

	public function scopeEmployee($query) {
		return $query->where('name', self::EMPLOYEE)->first();
	}

	public function scopePartner($query) {
		return $query->where('name', self::PARTNER)->first();
	}

	public function scopeShipper($query) {
		return $query->where('name', self::SHIPPER)->first();
	}

	public function scopeOrderByAsc($query, $column) {
		return $query->orderBy($column, 'asc');
	}

	public function scopeOrderByDesc($query, $column) {
		return $query->orderBy($column, 'desc');
	}

	public function users() {
		return $this->hasMany('App\Models\User','role_id');
	}


}
