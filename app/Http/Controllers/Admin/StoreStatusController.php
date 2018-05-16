<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\StoreStatus;
use DateTime;

class StoreStatusController extends Controller
{
    protected $statusForm;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function index()
    {
    	$status = StoreStatus::get();
    	return response($status, 200);
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
        $this->statusForm = (object)$request->all();
        $validator = Validator::make($request->all(),[
          'store_status_name' => 'unique:ec_store_status'
      ]);

        if($validator->fails()){
          return response($validator->errors()->first('store_status_name'), 422);
      }

      $status = new StoreStatus;
      $status->store_status_name = $this->statusForm->store_status_name;
      $status->store_status_description = $this->statusForm->store_status_description;
      $status->color = $this->statusForm->color;
      $status->created_at = new DateTime;
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
        $this->statusForm = (object)$request->all();
        $validator = Validator::make($request->all(),[
            'store_status_name' => Rule::unique('ec_store_status')->ignore($id),
        ]);
        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }

        $status = StoreStatus::find($id);
        $status->store_status_name = $request->input('store_status_name');
        $status->store_status_description = $request->input('store_status_description');
        $status->color = $request->input('color');
        $status->updated_at = new DateTime;
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
          StoreStatus::destroy($id);
          return response(['The status has been deleted'], 204);
      } catch (Exception $e) {
          return response(['Problem deleting the status', 500]);
      }
  }
}
