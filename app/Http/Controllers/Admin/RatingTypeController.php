<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\RatingType;
use App\Http\Resources\Admin\RatingTypeResource;
use Illuminate\Support\Str;

class RatingTypeController extends Controller
{
    public function __construct(RatingType $type) {
        $this->type = $type;
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = RatingType::orderByAsc('name')->get();
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
            'name' => 'unique:ec_rating_types,name'
        ]);

        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }

        $type = RatingType::create([
            'name' => $request->name,
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
            'name' => Rule::unique('ec_rating_types','name')->ignore($id),
        ]);
        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }
        $type = RatingType::find($id);
        $type->update([
            'name' => $request->name,
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
            RatingType::destroy($id);
            return response(['The rating type has been deleted'], 204);
        } catch (Exception $e) {
            return response(['Problem deleting the rating type', 500]);
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
                $res['type']   = new RatingTypeResource($data);
            break;

            case 'many':
                $res['types'] = RatingTypeResource::collection($data);
            break;
        }

        return response($res, $status);
    }
}
