<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Activity;
use App\Http\Resources\Admin\ActivityResource;

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
        $activities = Activity::orderByAsc('id')->get();
        return $this->respondSuccess('Get all activity', $activities, 200, 'many');
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
            'daysofweek' => 'unique:ec_activities,daysofweek'
        ]);

        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }

        $activity = Activity::create([
            'daysofweek' => $request->daysofweek,
            'number'     => $request->number
        ]);
        return $this->respondSuccess('Add activity', $activity, 201, 'one');
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
            'daysofweek' => Rule::unique('ec_activities','daysofweek')->ignore($id),
        ]);
        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }
        
        $activity = Activity::find($id);

        $activity->update([
            'daysofweek' => $request->daysofweek,
            'number'     => $request->number
        ]);

        return $this->respondSuccess('Edit activity', $activity, 200, 'one');
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
                $res['activity'] = new ActivityResource($data);
            break;

            case 'many':
                $res['activities'] = ActivityResource::collection($data);
            break;
        }

        return response($res, $status);
    }
}
