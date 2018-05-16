<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\City;
use App\Models\Store;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
class CouponController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$temp   = array();
		$coupon = Coupon::with(['status', 'stores' => function($query){
			return $query->with(['district' => function($query){
				return $query->with(['city' => function($query){
					return $query->select('ec_cities.id', 'city_name');
				}])->select('ec_districts.id', 'district_name', 'city_id');
			}])->select('ec_stores.id', 'store_name', 'district_id')->get();
		}])->select('ec_coupons.id', 'title', 'coupon', 'notes', 'discount_percent', 'max_coupons', 'coupon_used', 'started_at', 'ended_at', 'expires_at', 'status_id' ,'actived')->get();

		if(count($coupon)) {
			foreach($coupon as $data) {
				foreach($data->stores as $data1) {
					if(count($temp)==0) {
						array_push($temp, $data1->district['city_id']);
					} else {
						for($i = 0; $i < count($temp); $i++) {
							if($temp[$i] !== $data1->district['city_id']) {
								array_push($temp, $data1->district['city_id']);
							}
						}
					}
					$data->cities = $temp;
				}
				$temp = array();
			}
		}
		return response($coupon,200);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$coupon                   = new Coupon;
		$coupon->title            = $request->title;
		$coupon->coupon           = Str::upper($request->coupon);
		$coupon->notes            = $request->notes;
		$coupon->discount_percent = (int)$request->discount_percent;
		$coupon->max_coupons      = (int)$request->max_coupons;
		$coupon->coupon_used      =	0;
		$coupon->token 			  = hash_hmac('sha256', str_random(40), config('app.key'));
		$coupon->started_at       = $request->started_at;
		$coupon->ended_at         = Carbon::createFromFormat('Y-m-d H:i', $request->ended_at." 23:59")->toDateTimeString();
		$coupon->expires_at       = Carbon::createFromFormat('Y-m-d H:i', $request->ended_at." 23:59")->timestamp;
		$coupon->actived          = $request->actived;
		$coupon->status_id        = $request->status_id;
		$coupon->created_at       = new DateTime;
		$coupon->save();
		$coupon->status;
		return response($coupon, 201);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		$temp   = array();
		$coupon = Coupon::with(['status', 'stores' => function($query) {
			return $query->with('district');
		}])->findorFail($id);
		foreach($coupon->stores as $data) {
			if(count($temp)==0) {
				array_push($temp, $data->district['city_id']);
			} else {
				for($i = 0; $i < count($temp); $i++) {
					if($temp[$i] !== $data->district['city_id']) {
						array_push($temp, $data->district['city_id']);
					}
				}
			}
		}
		$coupon->cities = $temp;
		return response($coupon, 200);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function updateCoupon(Request $request, $id) {
		$coupon = Coupon::findorFail($id);
		$coupon->stores()->sync($request->storeIds);
		return response($coupon, 200);
	}

	public function update(Request $request, $id) {
		$coupon                   = Coupon::find($id);
		$coupon->title            = $request->title;
		$coupon->coupon           = Str::upper($request->coupon);
		$coupon->notes            = $request->notes;
		$coupon->discount_percent = (int)$request->discount_percent;
		$coupon->max_coupons      = (int)$request->max_coupons;
		$coupon->coupon_used      =	0;
		$coupon->started_at       = $request->started_at;
		$coupon->ended_at         = Carbon::createFromFormat('Y-m-d H:i', $request->ended_at." 23:59")->toDateTimeString();
		$coupon->expires_at       = Carbon::createFromFormat('Y-m-d H:i', $request->ended_at." 23:59")->timestamp;
		$coupon->actived          = $request->actived;
		$coupon->status_id        = $request->status_id;
		$coupon->updated_at       = new DateTime;
		$coupon->save();
		$coupon->status;
		return response($coupon, 200);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		try {
			Coupon::destroy($id);
			return response(['The coupon has been deleted'], 204);
		} catch (Exception $e) {
			return response(['Problem deleting the coupon', 500]);
		}
	}
}
