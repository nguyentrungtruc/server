<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Store;
use App\Http\Resources\Admin\StoreResource;
class StoreController extends Controller
{
    private static $page_size = 25;
    const PUBLIC_PATH = '/var/www/dofuu.com/public';
    public function __construct() {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $typeId   = $request->typeId;
        $cityId   = $request->cityId;
        $isShow   = $request->isShow;        
        $keywords = $request->keywords;
        $pageSize = self::$page_size;
        $offset   = 0;

        $stores = Store::show($isShow)->ofCity($cityId)->byTypeId($typeId)->likePlace($keywords)->orderByDesc('id')->limit($pageSize)->offset($offset)->paginate($pageSize);

        $pagination = $this->pagination($stores);
        
        return $this->respondSuccess('Search store', $stores, 200, 'many', $pagination);
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
            'name' => 'unique:ec_stores,store_name',
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->getMessages(), 422);
        }

        $store = Store::create([
            'store_name'    => $request->name,
            'store_slug'    => str_slug($request->name, '-'),
            'store_phone'   => $request->phone,
            'preparetime'   => (int) $request->prepareTime,
            'store_address' => $request->address,
            'lat'           => $request->lat,
            'lng'           => $request->lng,
            'priority'      => $request->priority,
            'discount'      => $request->discount,
            'district_id'   => (int) $request->districtId,
            'type_id'       => (int) $request->typeId,
            'status_id'     => $request->statusId,
            'verified'      => $request->isVerify,
            'store_show'    => $request->isShow,
            'views'         => 0,
            'likes'         => 0,            
        ]);

        return $this->respondSuccess('Add store', $store, 201, 'one');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $store = Store::findorFail($id);
        return $this->respondSuccess('Show store', $store->load('activities'), 200, 'one');
    }

    public function updateActivity(Request $request, $id) {
        $store = Store::findorFail($id);
        $data  = $request->data;
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['times'] = serialize($data[$i]['times']);
        }
        $store->activities()->detach();
        $store->activities()->sync($data);
        return $this->respondSuccess('Update activities', $store->load('activities'), 200, 'one');
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
            'name' => Rule::unique('ec_stores','store_name')->ignore($id)
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->getMessages(), 422);
        }
        $store = Store::find($id);
        $store->update([
            'store_name'    => $request->name,
            'store_slug'    => str_slug($request->name, '-'),
            'store_phone'   => $request->phone,
            'preparetime'   => (int) $request->prepareTime,
            'store_address' => $request->address,
            'lat'           => $request->lat,
            'lng'           => $request->lng,
            'priority'      => $request->priority,
            'discount'      => $request->discount,
            'district_id'   => (int) $request->districtId,
            'type_id'       => (int) $request->typeId,
            'status_id'     => $request->statusId,
            'verified'      => $request->isVerify,
            'store_show'    => $request->isShow,        
        ]);

        return $this->respondSuccess('Edit store', $store, 200, 'one');
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
            Store::destroy($id);
            return response(['The store has been deleted'], 204);
        } catch (Exception $e) {
            return response(['Problem deleting the store', 500]);
        }
    }

    /**
     * Update Avatar
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function updateAvatar(Request $request, $id)
    {
        $avatar    = $request->avatar;
        $storeId   = (int) $id;
        $store     = Store::findorFail($storeId);

        $this->handleRemoveImage($store->store_avatar);
        
        $dir       = '/storage/st/'. $storeId .'/av/';
        
        $path      = StoreController::PUBLIC_PATH . $dir;//PRODUCT
        // $path   = public_path($dir);//DEV
        $imageName = $this->handleImageName($store->name);
        $imageUrl  = $dir . $imageName;

        $this->handleUploadedImage($avatar, $path, $imageName);
  

        $store->update([
            'store_avatar' => $imageUrl,
        ]);

        return $this->respondSuccess('Update image', $store, 200, 'one');
    }

    /**
     * Handle Upload Image
    */
    protected function handleImageName($name) {
        return str_replace(' ', '-', 'dofuu-6' . str_replace('-', '', date('Y-m-d')) . '-6' . md5($name) . '-6' . time() . '.jpeg');
    }

    /**
     * Handle Upload Image
    */
    protected function handleUploadedImage($image, $path, $name)
    {
        if (!is_null($image)) {
            $data              = $image;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data              = base64_decode($data);

            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            file_put_contents($path . $name, $data);
        }
    }
    /**
     * Remove Image
     *
     * @param  string  $image
     * 
    */

    protected function handleRemoveImage($image)
    {

        if (!is_null($image)) {
            if (substr($image, 1, 7) === 'storage') {
                $url = StoreController::PUBLIC_PATH . $image;//PRODUCT
                // $url = public_path($image);//DEV
                unlink($url);
            }
        }
    }

    /**
     * Response a listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    protected function respondSuccess($message, $data, $status = 200, $type, $pagination = []) {
        $res = [
            'type'    => 'success',
            'message' => $message . ' successfully.',
        ];

        switch ($type) {

            case 'one':
            $res['store'] = new StoreResource($data);
            break;

            case 'many':
            $res['stores'] = StoreResource::collection($data);
            if (count($pagination) > 0) {
                $res['pagination'] = $pagination;
            }
            break;
        }

        return response($res, $status);
    }

    /**
     * Paginate listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    protected function pagination($data) {
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
