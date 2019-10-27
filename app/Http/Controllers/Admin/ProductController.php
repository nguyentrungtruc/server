<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Catalogue;
use App\Models\Product;
use App\Models\Store;
use App\Http\Resources\Admin\ProductResource;
use Illuminate\Support\Str;

class ProductController extends Controller
{
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
        $storeId  = (int) $request->storeId;
        $products = Store::findorFail($storeId)->products()->orderByAsc('name')->get();
        return $this->respondSuccess('Get all product', $products, 200, 'many');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name        = Str::lower($request->name);
        $catalogueId = $request->catalogueId;
        $find        = Product::exist($name, $catalogueId)->first();
        if(!is_null($find)){
            return response(['Already exists taken'], 422);
        }

        $product = Product::create([
            'name'         => Str::lower($request->name),
            '_name'        => Str::lower($request->_name),
            'have_topping' => $request->haveTopping,
            'count'        => 0,
            'priority'     => (int) $request->priority,
            'description'  => $request->description,
            'status_id'    => $request->statusId,
            'catalogue_id' => $request->catalogueId,
        ]);

        foreach($request->sizes as $size) {

            $product->sizes()->attach([
                $size['id'] => ['price' => $size['price']]
            ]);

        }

        return $this->respondSuccess('Add product', $product, 201, 'one');
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
        $name    = Str::lower($request->name);
        $catalogueId = $request->catalogueId;
        $find    = Product::exist($name, $catalogueId)->ignore($id)->first();
        if(!is_null($find)){
            return response(['Already exists taken'], 422);
        }
        $product = Product::find($id);
        $product->update([
            'name'         => Str::lower($request->name),
            '_name'        => Str::lower($request->_name),
            'have_topping' => $request->haveTopping,
            'count'        => 0,
            'priority'     => (int) $request->priority,
            'description'  => $request->description,
            'status_id'    => $request->statusId,
            'catalogue_id' => $request->catalogueId,
        ]);
        $product->sizes()->detach();
        foreach($request->sizes as $size) {

            $product->sizes()->attach([
                $size['id'] => ['price' => $size['price']]
            ]);

        }

        return $this->respondSuccess('Update product', $product, 200, 'one');
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
            Product::destroy($id);
            return response(['The product has been deleted'], 204);
        } catch(Exception $e) {
            return response(['The product has been deleted'], 204);
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
        $avatar      = $request->avatar;
        $storeId     = (int)$request->storeId;
        $productId   = (int) $id;
        $product     = Product::findorFail($productId);

        $this->handleRemoveImage($product->image);
        
        $dir       = '/storage/st/'. $storeId .'/pr/';
        
        $path      = StoreController::PUBLIC_PATH . $dir;//PRODUCT
        // $path   = public_path($dir);//DEV
        $imageName = $this->handleImageName($product->name);
        $imageUrl  = $dir . $imageName;

        $this->handleUploadedImage($avatar, $path, $imageName);
  

        $product->update([
            'image' => $imageUrl,
        ]);

        return $this->respondSuccess('Update image', $product, 200, 'one');
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
    protected function respondSuccess($message, $data, $status = 200, $type) {
        $res = [
            'status'  => 'success',
            'message' => $message . ' successfully.',
        ];

        switch ($type) {
            case 'one':
            $res['product']  = new ProductResource($data);
            break;

            case 'many':
            $res['products'] = ProductResource::collection($data);
            break;
        }

        return response($res, $status);
    }
}
