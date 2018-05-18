<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Catalogue;
use App\Http\Resources\Admin\CatalogueResource;
use Illuminate\Support\Str;
use DateTime;
class CatalogueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $sid       = (int)$id;
        $catalogue = Catalogue::where('store_id', '=', $sid)->get();
        return CatalogueResource::collection($catalogue);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
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
        $name = Str::lower($request->catalogue);
        $find = Catalogue::where('catalogue', '=', $name)->where('store_id', '=', $request->sid)->first();
        if(!is_null($find)){
            return response(['Already exists taken'], 422);
        }
        $catalogue             = new Catalogue;
        $catalogue->catalogue  = $request->catalogue;
        $catalogue->_catalogue = $request->_catalogue;
        $catalogue->slug       = str_slug($request->catalogue, '-');
        $catalogue->store_id   = $request->sid;
        $catalogue->created_at = new DateTime;
        $catalogue->save();
        return new CatalogueResource($catalogue);
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
        $name = Str::lower($request->catalogue);
        $find = Catalogue::where('catalogue', '=', $name)->where('store_id', '=', $request->sid)->where('id', '!=', $id)->first();
        if(!is_null($find)){
            return response(['Already exists taken'], 422);
        }
        $catalogue             = Catalogue::find($id);
        $catalogue->catalogue  = $request->catalogue;
        $catalogue->_catalogue = $request->_catalogue;
        $catalogue->slug       = str_slug($request->catalogue);
        $catalogue->updated_at = new DateTime;
        $catalogue->save();
        return new CatalogueResource($catalogue);
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
}
