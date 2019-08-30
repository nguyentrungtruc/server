<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Role;
use App\Http\Resources\Admin\UserResource;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private static $page_size = 25;

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
    public function index(Request $request)
    {
        $key = Str::lower($request->key);

        switch ($key) {
            case 'admin':
                $role = Role::admin();
                return $this->find($request, $role->id);
                break;
            case 'customer':
                $role = Role::customer();
                return $this->find($request, $role->id);
                break;
            case 'employee':
                $role = Role::employee();
                return $this->find($request, $role->id);
                break;
            case 'partner':
                $role = Role::partner();
                return $this->find($request, $role->id);
                break;
            case 'shipper':
                $role = Role::shipper();
                return $this->find($request, $role->id);
                break;  
        }
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
            'email' => 'unique:ec_users,email',
            'phone' => 'unique:ec_users,phone',
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->getMessages(), 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'birthday' => $request->birthday,
            'gender'   => $request->gender,
            'phone'    => $request->phone,
            'address'  => $request->address,
            'lat'      => $request->lat,
            'lng'      => $request->lng,
            'actived'  => $request->isActive,
            'banned'   => $request->isBan,
            'role_id'  => $request->roleId       
        ]);

        return $this->respondSuccess('Add user', $user, 201, 'one');
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
        $validator = Validator::make($request->all(), [
            'phone' => Rule::unique('ec_users','phone')->ignore($id),
            'email' => Rule::unique('ec_users','email')->ignore($id),
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->getMessages(), 422);
        }
        $user = User::find($id);
        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'birthday' => $request->birthday,
            'gender'   => $request->gender,
            'phone'    => $request->phone,
            'address'  => $request->address,
            'lat'      => $request->lat,
            'lng'      => $request->lng,
            'actived'  => $request->isActive,
            'banned'   => $request->isBan,
            'role_id'  => $request->roleId       
        ]);
        if(!empty($request->password)) {
            $user->update([
                'password' => bcrypt($request->password)
            ]);
        }

        return $this->respondSuccess('Edit user', $user, 200, 'one');
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

    private function find($request, $roleId) {
        $isActive   = $request->isActive;
        $isBan      = $request->isBan;
        $keywords   = $request->keywords;
        $pageSize   = self::$page_size;
        $offset     = 0;
        $users      = User::orderByDesc('id')->byRoleId($roleId)->active($isActive)->ban($isBan)->like($keywords)->limit($pageSize)->offset($offset)->paginate($pageSize);
        $pagination = $this->pagination($users);
        return $this->respondSuccess('Get all users', $users, 200, 'many', $pagination);
    }

    /**
     * Response a listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    private function respondSuccess($message, $data, $status = 200, $type, $pagination = []) {
        $res = [
            'type'    => 'success',
            'message' => $message . ' successfully.',
        ];

        switch ($type) {

            case 'one':
            $res['user'] = new UserResource($data);
            break;

            case 'many':
            $res['users'] = UserResource::collection($data);
            if (count($pagination) > 0) {
                $res['pagination'] = $pagination;
            }
            break;
        }

        return response($res, $status);
    }

    private function pagination($data) {
        return $pagination = [
            'total'       => $data->total(),
            'perPage'     => $data->perPage(),
            'from'        => $data->firstItem(),
            'currentPage' => $data->currentPage(),
            'to'          => $data->lastItem(),
            'lastPage'    => $data->lastPage(),
        ];
    }
}
