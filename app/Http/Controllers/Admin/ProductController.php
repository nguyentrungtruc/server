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
