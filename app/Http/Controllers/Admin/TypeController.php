<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Type;
use App\Http\Resources\Admin\TypeResource;

class TypeController extends Controller
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
        $types = Type::orderByAsc('type_name')->get();
        return $this->respondSuccess('Get all types', $types, 200, 'many');
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
            'name' => 'unique:ec_types,type_name'
        ]);

        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }

        $type = Type::create([
            'type_name' => $request->name,
            'type_slug' => str_slug($request->name, '-'),
            'code'      => $request->code,
            'type_icon' => $request->icon,
            'type_show' => $request->isShow,
        ]);
        return $this->respondSuccess('Add type', $type, 201, 'one');
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
            'name' => Rule::unique('ec_types','type_name')->ignore($id),
        ]);
        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }
        $type = Type::find($id);
        $type->update([
            'type_name' => $request->name,
            'type_slug' => str_slug($request->name, '-'),
            'code'      => $request->code,
            'type_icon' => $request->icon,
            'type_show' => $request->isShow,
        ]);
        return $this->respondSuccess('Edit type', $type, 200, 'one');
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
            Type::destroy($id);
            return response(['The type has been deleted'], 204);
        } catch (Exception $e) {
            return response(['Problem deleting the type', 500]);
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
                $res['type'] = new TypeResource($data);
            break;

            case 'many':
                $res['types'] = TypeResource::collection($data);
            break;
        }

        return response($res, $status);
    }
}
