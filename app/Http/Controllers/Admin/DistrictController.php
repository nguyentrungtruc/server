<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\District;
use App\Http\Resources\Admin\DistrictResource;

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
        $districts = District::orderByAsc('district_name')->get();
        return $this->respondSuccess('Get all districts', $districts, 200, 'many');
    }

    public function getByCity(Request $request) {
        $cityId    = $request->cityId;
        $districts = District::byCityId($cityId)->orderByAsc('district_name')->get();
        return $this->respondSuccess('Get all districts in city', $districts, 200, 'many');   
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
            'name' => 'unique:ec_districts,district_name'
        ]);

        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }

        $district = District::create([
            'district_name' => $request->name,
            'district_slug' => str_slug($request->name, '-'),
            'lat'           => $request->lat,
            'lng'           => $request->lng,
            'district_show' => $request->isShow,
            'city_id'       => $request->cityId
        ]);

        return $this->respondSuccess('Add district', $district, 201, 'one');
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
            'name' => Rule::unique('ec_districts','district_name')->ignore($id),
        ]);

        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }

        $district = District::find($id);
        $district->update([
            'district_name' => $request->name,
            'district_slug' => str_slug($request->name, '-'),
            'lat'           => $request->lat,
            'lng'           => $request->lng,
            'district_show' => $request->isShow,
            'city_id'       => $request->cityId
        ]);
        
        return $this->respondSuccess('Edit district', $district, 200, 'one');
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
                $res['district'] = new DistrictResource($data);
            break;

            case 'many':
                $res['districts'] = DistrictResource::collection($data);
            break;
        }

        return response($res, $status);
    }
}
