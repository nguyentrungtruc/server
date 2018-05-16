<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Role;
use DateTime;
class RoleController extends Controller
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
        $roles = Role::orderBy('name', 'ASC')->get();
        return response($roles, 200);
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
        $validator = Validator::make($request->all(), [
            'name' => 'unique:ec_roles,name'
        ]);
        if($validator->fails()) {
            return response($validator->errors()->getMessages(),422);
        }
        $role              = new Role;
        $role->name        = $request->name;
        $role->description = $request->description;
        $role->created_at  = new DateTime;
        $role->save();
        return response($role, 200);
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
        $role = Role::findorFail($id);
        return response($role, 200);
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
        $validator = Validator::make($request->all(), [
            'name' => Rule::unique('ec_roles')->ignore($id)
        ]);
        if($validator->fails()) {
            return response($validator->errors()->getMessages(),422);
        }
        $role              = Role::find($id);
        $role->name        = $request->name;
        $role->description = $request->description;
        $role->updated_at  = new DateTime;
        $role->save();
        return response($role, 201);
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
            Role::destroy($id);
            return response(['The role has been deleted'], 204);
        } catch(Exception $e) {
            return response(['Problem deleting the role'], 500);
        }
    }
}
