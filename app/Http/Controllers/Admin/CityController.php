<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\City;
use App\Models\Country;
use App\Http\Resources\Admin\CityResource;
use DateTime;
class CityController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        $cities = City::get();
        $res = [
            'type'    => 'success',
            'message' => 'Get Countries Successfully !!!',
            'data'    => CityResource::collection($cities) 
        ];
        return response($res, 200);
    }

    /**
     * Display a listing of the resource with store.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function cityWithStore() {
        $cities = City::with(['stores' => function($query) {
            return $query->orderBy('store_name');
        }])->orderBy('city_name')->get();
        return response($cities, 200);
    }

    /**
     * Display a listing of the city not delivery.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function citiesDoesntHaveDelivery() {
        $cities = City::doesntHave('deliveries')->get();
        $res = [
            'type'    => 'success',
            'message' => 'Create Countries Successfully !!!',
            'data'    => CityResource::collection($cities) 
        ];
        return response($res, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'unique:ec_cities,city_name'
        ]);

        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }

        $city             = new City;
        $city->city_name  = $request->name;
        $city->city_slug  = str_slug($request->name, '-');
        $city->zipcode    = $request->zipcode;
        $city->lat        = $request->lat;
        $city->lng        = $request->lng;
        $city->city_show  = $request->show;
        $city->country_id = $request->country_id;
        $city->created_at = new DateTime;
        $city->save();
        $city->service()->create([]);
        $res = [
            'type'    => 'success',
            'message' => 'Create Countries Successfully !!!',
            'data'    => new CityResource($city) 
        ];
        return response($res, 201);
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
            'city_name' => Rule::unique('ec_cities')->ignore($id),
        ]);
        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }
        $city             = City::find($id);
        $city->city_name  = $request->name;
        $city->city_slug  = str_slug($request->name, '-');
        $city->zipcode    = $request->zipcode;
        $city->lat        = $request->lat;
        $city->lng        = $request->lng;
        $city->city_show  = $request->show;
        $city->country_id = $request->country_id;
        $city->updated_at = new DateTime;
        $city->save();
        $res = [
            'type'    => 'success',
            'message' => 'Update Countries Successfully !!!',
            'data'    => new CityResource($city) 
        ];
        return response($res, 200);
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
            City::destroy($id);
            return response(['The city has been deleted'], 204);
        } catch (Exception $e) {
            return response(['Problem deleting the city', 500]);
        }
    }

    public function activeDelivery(Request $request, $id) {
        $cid  = $request->id;
        $city = City::where('id', '=', $cid)->first();
        if($city->service->delivery_actived) {
            $city->service->delivery_actived = false;
        } else {
            $city->service->delivery_actived = true;
        }
        $city->save();

        return new CityResource($city);
    }

    public function updateService(Request $request) {
        if($request->filled('id', 'deliveryActived', 'minAmount', 'maxAmount', 'maxRange')) {
            $city                      = City::where('id', '=', $request->id)->first();
            $service                   = $city->service;
            $service->delivery_actived = $request->deliveryActived;
            $service->min_amount       = $request->minAmount;
            $service->max_amount       = $request->maxAmount;
            $service->min_range        = $request->minRange;
            $service->max_range        = $request->maxRange;
            $service->save();
            $res = [
                'type'    => 'success',
                'message' => 'Update '.$city->city_name.' service successfully !!!',
                'data'    => new CityResource($city) 
            ];
            return response($res,200);
        }
    }
}
