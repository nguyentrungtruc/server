<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Role;
use App\Http\Resources\Admin\RoleResource;

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
        $roles = Role::orderByAsc('name')->get();   
        return $this->respondSuccess('Get all role', $roles, 200, 'many');
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
        $role = Role::create([
            'name'        => $request->name,
            'description' => $request->description
        ]);
        return $this->respondSuccess('Add role', $role, 201, 'one');
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
            'name' => Rule::unique('ec_roles','name')->ignore($id)
        ]);
        if($validator->fails()) {
            return response($validator->errors()->getMessages(),422);
        }

        $role = Role::find($id);
        $role->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return $this->respondSuccess('Edit role', $role, 200, 'one');
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
                $res['role']   = new RoleResource($data);
            break;

            case 'many':
                $res['roles'] = RoleResource::collection($data);
            break;
        }

        return response($res, $status);
    }
}
