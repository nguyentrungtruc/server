<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Catalogue;
use App\Models\Store;
use App\Http\Resources\Admin\CatalogueResource;
use Illuminate\Support\Str;

class CatalogueController extends Controller
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
        $storeId    = (int) $request->storeId;
        $catalogues = Store::findorFail($storeId)->catalogues()->orderByAsc('catalogue')->get();
        return $this->respondSuccess('Get all catalogues', $catalogues, 200, 'many');
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
        $storeId = $request->storeId;
        $find    = Catalogue::exist($name, $storeId)->first();
        if(!is_null($find)){
            return response(['Already exists taken'], 422);
        }

        $catalogue = Catalogue::create([
            'catalogue'      => Str::lower($request->name),
            '_catalogue'     => Str::lower($request->_name),
            'slug'           => str_slug($request->name, '-'),
            'priority'       => $request->priority,
            'catalogue_show' => $request->isShow,
            'store_id'       => $request->storeId
        ]);

        return $this->respondSuccess('Add catalogue', $catalogue, 201, 'one');
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
        $storeId = $request->storeId;
        $find    = Catalogue::exist($name, $storeId)->ignore($id)->first();
        if(!is_null($find)){
            return response(['Already exists taken'], 422);
        }
        $catalogue = Catalogue::find($id);
        $catalogue->update([
            'catalogue'      => Str::lower($request->name),
            '_catalogue'     => Str::lower($request->_name),
            'slug'           => str_slug($request->name, '-'),
            'priority'       => $request->priority,
            'catalogue_show' => $request->isShow,
            'store_id'       => $request->storeId
        ]);

        return $this->respondSuccess('Update catalogue', $catalogue, 200, 'one');
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
            Catalogue::destroy($id);
            return response(['The catalogue has been deleted'], 204);
        } catch(Exception $e) {
            return response(['The catalogue has been deleted'], 204);
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
            $res['catalogue']  = new CatalogueResource($data);
            break;

            case 'many':
            $res['catalogues'] = CatalogueResource::collection($data);
            break;
        }

        return response($res, $status);
    }
}
