<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Country;
use DateTime;
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
        $countries = Country::get();
        return response($countries, 200);
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
            'country_name' => 'unique:ec_countries'
        ]);

        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }

        $country               = new Country;
        $country->country_name = $request->country_name;
        $country->lang         = $request->lang;
        $country->country_slug = str_slug($request->country_name, '-');
        $country->lat          = $request->lat;
        $country->lng          = $request->lng;
        $country->dialingcode  = $request->dialingcode;
        $country->country_show = $request->country_show;
        $country->created_at   = new DateTime;
        $country->save();
        return response($country, 200);
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
            'country_name' => Rule::unique('ec_countries')->ignore($id),
        ]);
        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }
        $country               = Country::find($id);
        $country->country_name = $request->country_name;
        $country->lang         = $request->lang;
        $country->country_slug = str_slug($request->country_name, '-');
        $country->lat          = $request->lat;
        $country->lng          = $request->lng;
        $country->dialingcode  = $request->dialingcode;
        $country->country_show = $request->country_show;
        $country->updated_at   = new DateTime;
        $country->save();
        return response($country, 201);
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
}
