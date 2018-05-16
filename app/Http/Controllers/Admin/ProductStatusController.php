<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\ProductStatus;
use DateTime;
class ProductStatusController extends Controller
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
        $status = ProductStatus::get();
        return response($status, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
            'product_status_name' => 'unique:ec_product_status'
        ]);

        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }
        $status                             = new ProductStatus;
        $status->product_status_name        = $request->product_status_name;
        $status->product_status_description = $request->product_status_description;
        $status->color                      = $request->color;
        $status->created_at                 = new DateTime;
        $status->save();
        return response($status, 200);
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
        $validator = Validator::make($request->all(),[
            'product_status_name' => Rule::unique('ec_product_status')->ignore($id),
        ]);
        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }

        $status                             = ProductStatus::find($id);
        $status->product_status_name        = $request->product_status_name;
        $status->product_status_description = $request->product_status_description;
        $status->color                      = $request->color;
        $status->updated_at                 = new DateTime;
        $status->save();
        return response($status, 201);
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
            ProductStatus::destroy($id);
            return response(['The status has been deleted'], 204);
        } catch(Exception $e) {
            return response(['Problem deleting the status'], 500);
        }
    }
}
