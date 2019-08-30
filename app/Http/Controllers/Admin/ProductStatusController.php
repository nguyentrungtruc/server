<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\ProductStatus;
use App\Http\Resources\Admin\ProductStatusResource;

class ProductStatusController extends Controller
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
        $status = ProductStatus::orderByAsc('product_status_name')->get();
        return $this->respondSuccess('Get all product status', $status, 200, 'many');
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
          'name' => 'unique:ec_product_status,product_status_name'
        ]);

        if($validator->fails()){
          return response($validator->errors()->first('product_status_name'), 422);
        }

        $status = ProductStatus::create([
            'product_status_name'        => $request->name,
            'product_status_description' => $request->description,
            'color'                    => $request->color
        ]);
        return $this->respondSuccess('Add product status', $status, 201, 'one');
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
            'name' => Rule::unique('ec_product_status','product_status_name')->ignore($id),
        ]);
        if($validator->fails()){
            return response($validator->errors()->first('product_status_name'),422);
        }

        $status = ProductStatus::find($id);
        $status->update([
            'product_status_name'        => $request->name,
            'product_status_description' => $request->description,
            'color'                      => $request->color
        ]);
        return $this->respondSuccess('Edit product status', $status, 200, 'one');
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
            ProductStatus::destroy($id);
            return response(['The status has been deleted'], 204);
        } catch (Exception $e) {
            return response(['Problem deleting the status', 500]);
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
                $res['status'] = new ProductStatusResource($data);
            break;

            case 'many':
                $res['status'] = ProductStatusResource::collection($data);
            break;
        }

        return response($res, $status);
    }
}
