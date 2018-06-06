<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\City;
use App\Models\Store;
use App\Models\User;
use App\Models\StoreStatus;
use DateTime;
use App\Http\Resources\Admin\StoreResource;
class StoreController extends Controller
{
    protected $role_partner;
    protected $store_status;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->role_partner = Role::where('name', 'Partner')->first();
        $this->store_status = StoreStatus::where('store_status_name', 'Đóng cửa')->first(); 
        $this->middleware('auth:api');
    }

    public function index()
    {
        $store = Store::with(['user', 
            'type'     => function($query) {  
                return $query->select('id', 'type_name', 'type_icon');
            }, 
            'status'   => function($query) {
                return $query->select('id', 'store_status_name', 'color')->get();
            }, 
            'district' => function($query) {
                return $query->with(['city' => function($query) {
                    return $query->select('id', 'city_name')->get();
                }])->select('id', 'district_name', 'city_id')->get();
            }])->get();
        return StoreResource::collection($store);
        // return response($store, 200);
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
        $userForm  = $request->user;
        $storeForm = $request->store;
        if((int)$this->role_partner->id === (int)$userForm['role_id']) {
            $user             = new User;
            $user->name       = $userForm['name'];
            $user->email      = $userForm['email'];
            $user->password   = bcrypt($userForm['password']);
            $user->birthday   = $userForm['birthday'];
            $user->gender     = $userForm['gender'];
            $user->address    = $userForm['address'];
            $user->lat        = $userForm['lat'];
            $user->lng        = $userForm['lng'];
            $user->phone      = $userForm['phone'];
            $user->have_store = 1;
            $user->actived    = $userForm['actived'];
            $user->role_id    = $this->role_partner->id;
            $user->created_at = new DateTime;
            $user->save();
            //Add store
            $store                = new Store;
            $store->store_name    = $storeForm['name'];
            $store->store_slug    = str_slug($storeForm['name'], '-');
            $store->store_phone   = $storeForm['phone'];
            $store->preparetime   = (int)$storeForm['preparetime'];
            $store->store_address = $storeForm['address'];
            $store->lat           = $storeForm['lat'];
            $store->lng           = $storeForm['lng'];
            
            $store->verified      = $storeForm['verified'];
            $store->user_id       = $user->id;
            $store->district_id   = (int)$storeForm['district_id'];
            $store->type_id       = (int)$storeForm['type_id'];
            $store->status_id     = $this->store_status->id;
            $store->store_show    = $storeForm['show'];
            $store->created_at    = new DateTime;
            $store->save();
            // Store Avatar
            if(!is_null($storeForm['avatar'])) {
                $data              = $storeForm['avatar'];
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode (',', $data);
                $data              = base64_decode($data);
                $imageName         = str_replace(' ','-', 'dofuu-'.str_replace('-','', date('Y-m-d')).'-'.$store->id.'-'.time(). '.jpeg');
                $path = '/var/www/dofuu.com/public/storage/'.$store->user_id.'/stores/av/';
                if(!file_exists($path)){
                    mkdir($path, 0755, true);
                }

                file_put_contents($path . $imageName, $data);
                $imageUrl            = '/storage/'.$user->id.'/stores/av/'.$imageName;
                $store->store_avatar = $imageUrl;
                $store->save();
            }
            $store->user;
            $store->type;
            $store->status;
            $store->district->city;
            return new StoreResource($store);      
        }
        return response(['Something wrong'], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $store = Store::with(['user', 'type', 'status', 'district' => function($query){
            return $query->with('city');
        }])->findorFail($id);
        $store->activities;
        return new StoreResource($store);  
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
        //Update Store
        $store                = Store::find($id);
        $store->store_name    = $request->name;
        $store->store_slug    = str_slug($request->name, '-');
        $store->store_phone   = $request->phone;
        $store->preparetime   = (int)$request->preparetime;
        $store->store_address = $request->address;
        $store->lat           = $request->lat;
        $store->lng           = $request->lng;
        $store->verified      = $request->verified;
        $store->district_id   = (int)$request->district_id;
        $store->type_id       = (int)$request->type_id;
        $store->status_id     = $this->store_status->id;
        $store->store_show    = $request->show;
        $store->updated_at    = new DateTime;
        // Store Avatar
        if(!is_null($request->avatar)) {
            if($store->store_avatar !== $request->avatar && !is_null($store->store_avatar)) {
                $url               = $store->store_avatar;
                $oldPath = '/var/www/dofuu.com/public';
                unlink($oldPath.$url);
                $data              = $request->avatar;
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode (',', $data);
                $data              = base64_decode($data);
                $imageName         = str_replace(' ','-', 'dofuu-'.str_replace('-','', date('Y-m-d')).'-'.$store->id.'-'.time(). '.jpeg');

                // //path linux
                // $path = '/var/www/dofuu.xyz/public/storage/'.$store->user_id.'/stores/av/';
                $path = '/var/www/dofuu.com/public/storage/'.$store->user_id.'/stores/av/';
                // $path = public_path('storage/'.$store->user_id.'/stores/av/');
                if(!file_exists($path)){
                    mkdir($path, 0755, true);
                }
                file_put_contents($path . $imageName, $data);
                $imageUrl            = '/storage/'.$store->user_id.'/stores/av/'.$imageName;
                $store->store_avatar = $imageUrl;

            } else if(is_null($store->store_avatar)) {
                $data              = $request->avatar;
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode (',', $data);
                $data              = base64_decode($data);
                $imageName         =str_replace(' ','-', 'dofuu-'.str_replace('-','', date('Y-m-d')).'-'.$store->id.'-'.time(). '.jpeg');
                // $path              = public_path('storage/'.$store->user_id.'/stores/av/');
                // $path = '/var/www/dofuu.xyz/public/storage/'.$store->user_id.'/stores/av/';
                $path = '/var/www/dofuu.com/public/storage/'.$store->user_id.'/stores/av/';
                if(!file_exists($path)){
                    mkdir($path, 0755, true);
                }
                file_put_contents($path . $imageName, $data);
                $imageUrl            = '/storage/'.$store->user_id.'/stores/av/'.$imageName;
                $store->store_avatar = $imageUrl;
            }

        }
        $store->save();
        $store->user;
        $store->type;
        $store->status;
        $store->district->city;
        return new StoreResource($store);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateActivity(Request $request, $id) {
        $store = Store::findorFail($id);
        $data  = $request->data;
        for($i = 0; $i < count($data); $i++) {
            $data[$i]['times'] = serialize($data[$i]['times']);
        }
        $store->activities()->detach();
        $store->activities()->sync($data);
        $res = [
            'type'    => 'success',
            'message' => 'Update activities successfully',
            'data'    => new StoreResource($store->load('activities'))
        ];
        return response($res, 200);
    }
}
