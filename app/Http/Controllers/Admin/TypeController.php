<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Type;
use DateTime;

class TypeController extends Controller
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
    	$types = Type::get();
    	return response($types, 200);
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
    		'type_name' => 'unique:ec_types'
    	]);

    	if($validator->fails()){
    		return response($validator->errors()->getMessages(),422);
    	}

    	$type = new Type;
    	$type->type_name = $request->type_name;
    	$type->type_slug = str_slug($request->type_name, '-');
    	$type->type_icon = $request->type_icon;
    	$type->type_show = $request->type_show;
    	$type->created_at = new DateTime;
    	$type->save();
    	return response($type, 200);
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
    		'type_name' => Rule::unique('ec_types')->ignore($id),
    	]);
    	if($validator->fails()){
    		return response($validator->errors()->getMessages(),422);
    	}
    	$type = Type::find($id);
    	$type->type_name = $request->type_name;
    	$type->type_slug = str_slug($request->type_name, '-');
    	$type->type_icon = $request->type_icon;
    	$type->type_show = $request->type_show;
    	$type->updated_at = new DateTime;
    	$type->save();
    	return response($type, 201);
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
    		Type::destroy($id);
    		return response(['The type has been deleted'], 204);
    	} catch (Exception $e) {
    		return response(['Problem deleting the type', 500]);
    	}
    }
}
