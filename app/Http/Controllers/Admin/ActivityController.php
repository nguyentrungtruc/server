<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Resources\Admin\ActivityResource;
use App\Models\Activity;
use DateTime;
class ActivityController extends Controller
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
    	$activity = Activity::get();
        $res = [
            'type'    => 'success',
            'message' => 'Get activity successfully',
            'data'    => ActivityResource::collection($activity)
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
        $validator = Validator::make($request->all(),[
            'daysofweek' => 'unique:ec_activities'
        ]);

        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }

        $activity             = new Activity;
        $activity->daysofweek = $request->daysofweek;
        $activity->number     = $request->number;
        $activity->created_at = new DateTime;
        $activity->save();
        $res = [
            'type'    => 'success',
            'message' => 'Get activity successfully',
            'data'    => new ActivityResource($activity)
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
        $activity             = Activity::find($id);
        $activity->daysofweek = $request->daysofweek;
        $activity->number     = $request->number;
        $activity->updated_at = new DateTime;
        $activity->save();
        $res = [
            'type'    => 'success',
            'message' => 'Get activity successfully',
            'data'    => new ActivityResource($activity)
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
    		Activity::destroy($id);
    		return response(['The activity has been deleted'], 204);
    	} catch(Exception $e) {
    		return response(['Problem deleting the activity', 500]);
    	}
    }
}
