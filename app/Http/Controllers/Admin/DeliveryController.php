<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;
use DateTime;
use App\Http\Resources\Admin\DeliveryResource;
class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::has('deliveries')->with(['deliveries' => function($query){
            return $query->orderBy('range_id', 'asc');
        }])->get();
        $res = [
            'type'    => 'success',
            'message' => 'Create Countries Successfully !!!',
            'data'    => DeliveryResource::collection($cities->load('service')) 
        ];
        return response($res, 200);
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
        $city = City::findorFail($request->city_id);
        foreach($request->ranges as $data) {
            $city->deliveries()->syncWithoutDetaching([$data['id'] => ['price' => $data['price']]]);
        }
        $res = [
            'type'    => 'success',
            'message' => 'Create Countries Successfully !!!',
            'data'    => new DeliveryResource($city->load('service')) 
        ];
        return response($res, 201);
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
        if($request->id == $id) {
            $city = City::findorFail($request->id);
            foreach($request->ranges as $data) {
                $city->deliveries()->syncWithoutDetaching([$data['id'] => ['price' => $data['price']]]);
            }
            $res = [
                'type'    => 'success',
                'message' => 'Create Countries Successfully !!!',
                'data'    => new DeliveryResource($city->load('service')) 
            ];
            return response($res, 200);
        }
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
            $city = City::findorFail($id);
            $city->deliveries()->detach();
            return response(['The city has been detached deliveries'], 204);
        } catch(Exception $e) {
            return response(['Problem detached the deliveries', 500]);
        }
    }
}
