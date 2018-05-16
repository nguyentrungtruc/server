<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Resources\Admin\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Role;
use App\Models\Store;
use DateTime;
class UserController extends Controller
{
    protected $partner;

    public function __construct() {
        $this->partner = Role::where('name', 'Partner')->first();
        $this->shipper = Role::where('name', 'Shipper')->first();
        $this->middleware('auth:api');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function getShipper() {
        $users = User::where('role_id', '=', $this->shipper->id)->get();
        $res   = [
            'type'    => 'success',
            'message' => 'Get shipper information successfully.',
            'data'    => UserResource::collection($users)
        ];
        return response($res, 200);
    }

    public function index()
    {
        $users = User::orderBy('name')->get();
        foreach($users as $user) {
            (object)$user->role;
        }
        return response($users, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->birthday = $request->birthday;
        $user->gender = $request->gender;
        $user->address = $request->address;
        $user->lat = $request->lat;
        $user->lng = $request->lng;
        $user->phone = $request->phone;
        $user->actived = $request->actived;
        $user->role_id = $request->role_id;
        if((int)$request->role_id === $this->partner->id ) {
            $user->have_store = 1;
        }
        $user->created_at = new DateTime;
        $user->save();
        //Create store with role partner
        if((int)$request->role_id === $this->partner->id) {
            $user->store()->save(new Store);
        }
        $res = User::with('role')->find($user->id);
        return response($res, 200);
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
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if(!empty($request->password)) {
            $user->password = bcrypt($request->password);       
        }
        $user->birthday = $request->birthday;
        $user->gender = $request->gender;
        $user->address = $request->address;
        $user->lat = $request->lat;
        $user->lng = $request->lng;
        $user->phone = $request->phone;
        $user->actived = $request->actived;
        $user->role_id = $request->role_id;
        if((int)$request->role_id === $this->partner->id ) {
            $user->have_store = 1;
        } else {
            $user->have_store = 0;
        }
        $user->updated_at = new DateTime;
        $user->save();
        //Create store with role partner
        if((int)$request->role_id === $this->partner->id) {
            if(empty($user->store)) {
                $user->store()->save(new Store);      
            }            
        }
        $res = User::with('role')->find($user->id);
        return response($res, 201);
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
            User::destroy($id);
            return response(['The user has been deleted'], 204);
        } catch (Exception $e) {
            return response(['Problem deleting the user', 500]);
        }
    }
}
