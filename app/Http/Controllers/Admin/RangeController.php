<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Range;
use DateTime;
use Illuminate\Support\Facades\Auth;
class RangeController extends Controller
{
    public function __construct() {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $range = Range::get();

        return response($range, 200);   
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
        $range              = new Range;
        $range->from        = $request->from;
        $range->to          = $request->to;
        $range->description = $request->description;
        $range->created_at  = new DateTime;
        $range->save();

        return response($range, 201);
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
        $range              = Range::find($id);
        $range->from        = $request->from;
        $range->to          = $request->to;
        $range->description = $request->description;
        $range->created_at  = new DateTime;
        $range->save();

        return response($range, 201);
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
            Range::destroy($id);
            return response(['The range has been deleted'], 204);
        } catch(Exception $e) {
            return response(['Problem deleting the range'], 500);
        }
    }
}
