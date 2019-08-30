<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\OrderStatus;
use App\Http\Resources\Admin\OrderStatusResource;

class OrderStatusController extends Controller
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
        $status = OrderStatus::orderByAsc('number_order')->get();
        return $this->respondSuccess('Get all order status', $status, 200, 'many');
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
          'name' => 'unique:ec_order_status,order_status_name'
        ]);

        if($validator->fails()){
          return response($validator->errors()->first('order_status_name'), 422);
        }

        $status = OrderStatus::create([
            'order_status_name'        => $request->name,
            'order_status_description' => $request->description,
            'number_order'             => $request->step,
            'color'                    => $request->color
        ]);
        return $this->respondSuccess('Add order status', $status, 201, 'one');
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
            'name' => Rule::unique('ec_order_status','order_status_name')->ignore($id),
        ]);
        if($validator->fails()){
            return response($validator->errors()->first('order_status_name'),422);
        }

        $status = OrderStatus::find($id);
        $status->update([
            'order_status_name'        => $request->name,
            'order_status_description' => $request->description,
            'number_order'             => $request->step,
            'color'                    => $request->color
        ]);
        return $this->respondSuccess('Edit order status', $status, 200, 'one');
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
            OrderStatus::destroy($id);
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
                $res['status'] = new OrderStatusResource($data);
            break;

            case 'many':
                $res['status'] = OrderStatusResource::collection($data);
            break;
        }

        return response($res, $status);
    }
}
