<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Topping;
use App\Models\Store;
use App\Http\Resources\Admin\ToppingResource;
use Illuminate\Support\Str;

class ToppingController extends Controller
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
        $storeId = (int) $request->storeId;
        $toppings = Store::findorFail($storeId)->toppings()->orderByAsc('name')->get();
        return $this->respondSuccess('Get all toppings', $toppings, 200, 'many');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name    = Str::lower($request->name);
        $storeId = (int) $request->storeId;
        $find    = Topping::exist($name, $storeId)->first();
        if(!is_null($find)){
            return response(['Already exists taken'], 422);
        }

        $topping = Topping::create([
            'name'         => Str::lower($request->name),
            '_name'        => Str::lower($request->_name),
            'price'        => $request->price,
            'topping_show' => $request->isShow,
            'store_id'     => $request->storeId
        ]);

        return $this->respondSuccess('Add topping', $topping, 201, 'one');
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
        $storeId = (int) $request->storeId;
        $find    = Topping::exist($name, $storeId)->ignore($id)->first();
        if(!is_null($find)){
            return response(['Already exists taken'], 422);
        }
        $topping = Topping::find($id);
        $topping->update([
            'name'         => Str::lower($request->name),
            '_name'        => Str::lower($request->_name),
            'price'        => $request->price,
            'topping_show' => $request->isShow,
            'store_id'     => $request->storeId
        ]);

        return $this->respondSuccess('Update topping', $topping, 200, 'one');
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
            Topping::destroy($id);
            return response(['The topping has been deleted'], 204);
        } catch(Exception $e) {
            return response(['The topping has been deleted'], 204);
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
            $res['topping']  = new ToppingResource($data);
            break;

            case 'many':
            $res['toppings'] = ToppingResource::collection($data);
            break;
        }

        return response($res, $status);
    }
}
