<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\StoreStatus;
use App\Http\Resources\Admin\StoreStatusResource;
class StoreStatusController extends Controller
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
        $status = StoreStatus::orderByAsc('store_status_name')->get();
        return $this->respondSuccess('Get all store status', $status, 200, 'many');
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
          'name' => 'unique:ec_store_status,store_status_name'
        ]);

        if($validator->fails()){
          return response($validator->errors()->first('store_status_name'), 422);
        }

        $status = StoreStatus::create([
            'store_status_name'        => $request->name,
            'store_status_description' => $request->description,
            'color'                    => $request->color
        ]);
        return $this->respondSuccess('Add store status', $status, 201, 'one');
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
            'name' => Rule::unique('ec_store_status','store_status_name')->ignore($id),
        ]);
        if($validator->fails()){
            return response($validator->errors()->first('store_status_name'),422);
        }

        $status = StoreStatus::find($id);
        $status->update([
            'store_status_name'        => $request->name,
            'store_status_description' => $request->description,
            'color'                    => $request->color
        ]);
        return $this->respondSuccess('Edit store status', $status, 200, 'one');
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
            StoreStatus::destroy($id);
            return response(['The status has been deleted'], 204);
        } catch (Exception $e) {
            return response(['Problem deleting the status', 500]);
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
                $res['status'] = new StoreStatusResource($data);
            break;

            case 'many':
                $res['status'] = StoreStatusResource::collection($data);
            break;
        }

        return response($res, $status);
    }
}
