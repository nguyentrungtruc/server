<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ProductResource;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductStatus;
use DateTime;
use Illuminate\Support\Str;
class ProductController extends Controller
{
    protected $status;
    public function __construct() {
        $this->status = ProductStatus::where('product_status_name', 'còn')->first();
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $sid     = $id;
        $product = Product::whereHas('catalogue', function($query) use ($sid) {
            return $query->where('store_id', '=', $sid);
        })->get();
        return ProductResource::collection($product);
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
        $sid  = $request->sid;
        $name = Str::lower($request->name);
        $find = Store::with(['products' => function($query) use ($name) {
            return $query->where('name', '=', $name);
        }])->where('id','=', $sid)->first();
        if(count($find->products)>0){
            return response(['Already exists taken'], 422);
        }
        $product              = new Product;
        $product->name        = $request->name;
        $product->_name       = $request->_name;
        $product->price       = $request->price;
        $product->description = $request->description;
        if(!is_null($request->image)) {

            $user = User::whereHas('store', function($query) use ($sid) {
                return $query->where('id', '=', $sid);
            })->first();
            $data              = $request->image;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode (',', $data);
            $data              = base64_decode($data);
            $imageName         =str_replace(' ','-', 'dofuu-'.str_replace('-','', date('Y-m-d')).'-'.$request->$sid.'-'.time(). '.jpeg');
            // $path              = public_path('storage/'.$user->id.'/stores/products/');
            $path = '/var/www/dofuu.com/public/storage/'.$user->id.'/stores/products/';
            if(!file_exists($path)){
                mkdir($path, 0755, true);
            }
            file_put_contents($path . $imageName, $data);
            $imageUrl              = '/storage/'.$user->id.'/stores/products/'.$imageName;
            $product->image        = $imageUrl;
        }
        $product->count        = 0;
        $product->status_id    = $request->status_id;
        $product->catalogue_id = $request->catalogue_id;
        $product->created_at   = new DateTime;
        $product->save();
        return new ProductResource($product);
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
        $sid  = $request->sid;
        $name = Str::lower($request->name);
        $find = Store::with(['products' => function($query) use ($name, $id) {
            return $query->where('name', '=', $name)->where('ec_products.id', '!=', $id);
        }])->where('id','=', $sid)->first();
        if(count($find->products)>0){
            return response(['Đã tồn tại'], 422);
        }
        $product              = Product::find($id);
        $product->name        = $request->name;
        $product->_name       = $request->_name;
        $product->price       = $request->price;
        $product->description = $request->description;
        if(!is_null($request->image)) {
            if($product->image !== $request->image && !is_null($product->image)) {
                $url = $product->image;
                $oldPath = '/var/www/dofuu.com/public';
                // unlink(public_path().$url);
                unlink($oldPath.$url);
                $user = User::whereHas('store', function($query) use ($sid) {
                    return $query->where('id', '=', $sid);
                })->first();
                $data              = $request->image;
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode (',', $data);
                $data              = base64_decode($data);
                $imageName         =str_replace(' ','-', 'dofuu-'.str_replace('-','', date('Y-m-d')).'-'.$sid.'-'.time(). '.jpeg');
                // $path              = public_path('storage/'.$user->id.'/stores/products/');
                $path = '/var/www/dofuu.com/public/storage/'.$user->id.'/stores/products/';
                if(!file_exists($path)){
                    mkdir($path, 0755, true);
                }
                file_put_contents($path . $imageName, $data);
                $imageUrl          = '/storage/'.$user->id.'/stores/products/'.$imageName;
                $product->image    = $imageUrl;
            } else if(is_null($product->image)) {
                $user = User::whereHas('store', function($query) use ($sid) {
                    return $query->where('id', '=', $sid);
                })->first();
                $data              = $request->image;
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode (',', $data);
                $data              = base64_decode($data);
                $imageName         =str_replace(' ','-', 'dofuu-'.str_replace('-','', date('Y-m-d')).'-'.$request->$sid.'-'.time(). '.jpeg');
                // $path              = public_path('storage/'.$user->id.'/stores/products/');
                $path = '/var/www/dofuu.com/public/storage/'.$user->id.'/stores/products/';
                if(!file_exists($path)){
                    mkdir($path, 0755, true);
                }
                file_put_contents($path . $imageName, $data);
                $imageUrl          = '/storage/'.$user->id.'/stores/products/'.$imageName;
                $product->image    = $imageUrl;
            }
        }
        $product->status_id    = $request->status_id;
        $product->catalogue_id = $request->catalogue_id;
        $product->updated_at   = new DateTime;
        $product->save();
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $product = Product::findorFail($id);
            if(!is_null($product->image)) {
                $path = '/var/www/dofuu.com/public/storage/'.$user->id.'/stores/products/';
                $url = $product->image;
                unlink($path.$url);
            }
            Product::destroy($id);
            return response(['The product has been deleted'], 204);
        }catch(Exception $e) {
            return response(['Problem deleting the product'], 500);
        }
    }
}
