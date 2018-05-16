<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use DateTime;
use App\Models\OrderStatus;
use App\Http\Resources\Admin\OrderStatusResource;
use App\Http\Requests\Admin\OrderStatusRequest;
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
        $status = OrderStatus::get();
        $res    = [
            'type'    => 'success',
            'message' => 'Get order status successfully!',
            'data'    => OrderStatusResource::collection($status)
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
    public function store(OrderStatusRequest $request)
    {
        $status                           = new OrderStatus;
        $status->order_status_name        = $request->name;
        $status->order_status_description = $request->description;
        $status->color                    = $request->color;
        $status->number_order             = $request->numberOrder;
        $status->save();
        $res                              = [
            'type'    => 'success',
            'message' => 'Created status successfully!',
            'data'    => new OrderStatusResource($status)
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
        $validator = Validator::make($request->all(),[
            'name'        => Rule::unique('ec_order_status','order_status_name')->ignore($id),
            'numberOrder' => Rule::unique('ec_order_status','number_order')->ignore($id),
        ]);
        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }
        $status                           = OrderStatus::findorFail($id);
        $status->order_status_name        = $request->name;
        $status->order_status_description = $request->description;
        $status->color                    = $request->color;
        $status->number_order             = $request->numberOrder;
        $res                              = [
            'type'    => 'success',
            'message' => 'Updated status successfully',
            'data'    => new OrderStatusResource($status)
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
            OrderStatus::destroy($id);
            $res = [
                'type'    => 'success',
                'message' => 'The status has been deleted',
                'data'    => []
            ];
            return response($res, 204);
        } catch(Exception $e) {
            $res = [
                'type'    => 'error',
                'message' => 'Problem deleting the status',
                'data'    => []
            ];
            return response($res, 500);
        }
    }
}
