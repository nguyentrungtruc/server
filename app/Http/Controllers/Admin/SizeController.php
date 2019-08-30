<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Size;
use App\Http\Resources\Admin\SizeResource;
use Illuminate\Support\Str;

class SizeController extends Controller
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
        $sizes = Size::get();
        return $this->respondSuccess('Get all sizes', $sizes, 200, 'many');
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
            'name' => 'unique:ec_sizes,name'
        ]);

        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }

        $size = Size::create([
			'name'  => $request->name,
			'_name' => $request->_name,
        ]);

        return $this->respondSuccess('Add size', $size, 201, 'one');
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
            'name' => Rule::unique('ec_sizes','name')->ignore($id),
        ]);

        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }

        $size = Size::find($id);
        $size->update([
            'name'  => $request->name,
			'_name' => $request->_name,
        ]);
        
        return $this->respondSuccess('Edit size', $size, 200, 'one');
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
            Size::destroy($id);
            return response(['The size has been deleted'], 204);
        } catch (Exception $e) {
            return response(['Problem deleting the size', 500]);
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
                $res['size'] = new SizeResource($data);
            break;

            case 'many':
                $res['sizes'] = SizeResource::collection($data);
            break;
        }

        return response($res, $status);
    }
}
