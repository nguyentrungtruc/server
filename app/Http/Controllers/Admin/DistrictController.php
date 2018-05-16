<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\City;
use App\Models\District;
use DateTime;
class DistrictController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = District::with('city')->get();
        return response($cities, 200);
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
            'district_name' => 'unique:ec_districts'
        ]);

        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }

        $district                = new District;
        $district->district_name = $request->district_name;
        $district->district_slug = str_slug($request->district_name, '-');
        $district->lat           = $request->lat;
        $district->lng           = $request->lng;
        $district->district_show = $request->district_show;
        $district->city_id       = $request->city_id;
        $district->created_at    = new DateTime;
        $district->save();
        $district->city;
        return response($district, 200);
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
            'district_name' => Rule::unique('ec_districts')->ignore($id),
        ]);

        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }

        $district                = District::with('city')->find($id);
        $district->district_name = $request->district_name;
        $district->district_slug = str_slug($request->district_name, '-');
        $district->lat           = $request->lat;
        $district->lng           = $request->lng;
        $district->district_show = $request->district_show;
        $district->city_id       = $request->city_id;
        $district->updated_at    = new DateTime;
        $district->save();
        $district->city;
        return response($district, 201);
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
            District::destroy($id);
            return response(['The district has been deleted'], 204);
        } catch (Exception $e) {
            return response(['Problem deleting the district', 500]);
        }
    }
}
