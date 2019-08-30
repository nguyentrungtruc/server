<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\City;
use App\Http\Resources\Admin\CityResource;
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
        $cities = City::orderByAsc('city_name')->get();
        return $this->respondSuccess('Get all cities', $cities, 200, 'many');
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
            'name' => 'unique:ec_cities,city_name'
        ]);

        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }

        $city = City::create([
            'city_name'  => $request->name,
            'city_slug'  => str_slug($request->name, '-'),
            'zipcode'    => $request->zipCode,
            'lat'        => $request->lat,
            'lng'        => $request->lng,
            'city_show'  => $request->isShow,
            'country_id' => $request->countryId
        ]);

        return $this->respondSuccess('Add city', $city, 201, 'one');
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
            'name' => Rule::unique('ec_cities','city_name')->ignore($id),
        ]);
        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }

        $city = City::find($id);
        $city->update([
            'city_name'  => $request->name,
            'city_slug'  => str_slug($request->name, '-'),
            'zipcode'    => $request->zipCode,
            'lat'        => $request->lat,
            'lng'        => $request->lng,
            'city_show'  => $request->isShow,
            'country_id' => $request->countryId
        ]);

        return $this->respondSuccess('Add city', $city, 200, 'one');
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

    /**
     * Response a listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    protected function respondSuccess($message, $data, $status = 200, $type) {
        $res = [
            'status'  => 'success',
            'message' => $message . ' successfully.',
        ];

        switch ($type) {

            case 'one':
                $res['city'] = new CityResource($data);
            break;

            case 'many':
                $res['cities'] = CityResource::collection($data);
            break;
        }

        return response($res, $status);
    }
}
