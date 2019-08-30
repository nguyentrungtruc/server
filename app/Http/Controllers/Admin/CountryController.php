<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Country;
use App\Http\Resources\Admin\CountryResource;

class CountryController extends Controller
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
        $countries = Country::orderByAsc('country_name')->get();
        return $this->respondSuccess('Get all countries', $countries, 200, 'many');
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
            'name' => 'unique:ec_countries,country_name'
        ]);

        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }

        $country = Country::create([
            'country_name' => $request->name,
            'lang'         => $request->lang,
            'country_slug' => str_slug($request->name, '-'),
            'lat'          => $request->lat,
            'lng'          => $request->lng,
            'dialingcode'  => $request->dialingCode,
            'country_show' => $request->isShow
        ]);

        return $this->respondSuccess('Add country', $country, 201, 'one');
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
            'name' => Rule::unique('ec_countries','country_name')->ignore($id),
        ]);
        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }
        
        $country               = Country::find($id);
        $country->update([
            'country_name' => $request->name,
            'lang'         => $request->lang,
            'country_slug' => str_slug($request->name, '-'),
            'lat'          => $request->lat,
            'lng'          => $request->lng,
            'dialingcode'  => $request->dialingCode,
            'country_show' => $request->isShow
        ]);
        
        return $this->respondSuccess('Edit country', $country, 200, 'one');
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
            Country::destroy($id);
            return response(['The country has been deleted'], 204);
        } catch (Exception $e) {
            return response(['Problem deleting the country', 500]);
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
                $res['country']   = new CountryResource($data);
            break;

            case 'many':
                $res['countries'] = CountryResource::collection($data);
            break;
        }

        return response($res, $status);
    }

    // protected function pagination($data) {
    //     return $pagination = [
    //         'total'        => $data->total(),
    //         'per_page'     => $data->perPage(),
    //         'from'         => $data->firstItem(),
    //         'current_page' => $data->currentPage(),
    //         'to'           => $data->lastItem(),
    //         'last_page'    => $data->lastPage(),
    //     ];
    // }

    // protected function infiniteScroll($offset, $count, $pageSize) {
    //     if($count-($offset+$pageSize)> 0) {
    //         return false;
    //     } else {
    //         return true;
    //     }
    // }
}
