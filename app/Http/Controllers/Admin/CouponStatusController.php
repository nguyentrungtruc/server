<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\CouponStatus;
use DateTime;
class CouponStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$status = CouponStatus::get();
    	return response($status, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$validator = Validator::make($request->all(),[
    		'coupon_status_name' => 'unique:ec_coupon_status'
    	]);

    	if($validator->fails()){
    		return response($validator->errors()->getMessages(),422);
    	}
    	$status = new CouponStatus;
    	$status->coupon_status_name = $request->coupon_status_name;
    	$status->coupon_status_description = $request->coupon_status_description;
    	$status->color = $request->color;
    	$status->created_at = new DateTime;
    	$status->save();
    	return response($status, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    	$validator = Validator::make($request->all(),[
    		'coupon_status_name' => Rule::unique('ec_coupon_status')->ignore($id),
    	]);
    	if($validator->fails()){
    		return response($validator->errors()->getMessages(),422);
    	}

        $status                            = CouponStatus::find($id);
        $status->coupon_status_name        = $request->coupon_status_name;
        $status->coupon_status_description = $request->coupon_status_description;
        $status->color                     = $request->color;
        $status->updated_at                = new DateTime;
    	$status->save();
    	return response($status, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	try {
    		CouponStatus::destroy($id);
    		return response(['The status has been deleted'], 204);
    	} catch(Exception $e) {
    		return response(['Problem deleting the status'], 500);
    	}
    }
}
