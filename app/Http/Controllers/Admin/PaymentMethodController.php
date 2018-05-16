<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Http\Resources\Admin\PaymentMethodResource;
use App\Http\Requests\Admin\PaymentMethodRequest;
use DateTime;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = PaymentMethod::get();
        $res      = [
            'type'    => 'success',
            'message' => 'Get payment successfully.',
            'data'    => PaymentMethodResource::collection($payments)
        ];    
        return response($res, 200);
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
    public function store(PaymentMethodRequest $request)
    {
        $payment               = new PaymentMethod;
        $payment->payment_name = $request->paymentName;
        $payment->description  = $request->description;
        $payment->show         = $request->show;
        $payment->created_at   = new DateTime;
        $payment->save();

        $res = [
            'type'    => 'success',
            'message' => 'Created payment method successfully.',
            'data'    => new PaymentMethodResource($payment)
        ];
        return response($res, 201);
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
        $validator = Validator::make($request->all(),[
            'paymentName' => Rule::unique('ec_payment_methods','payment_name')->ignore($id),
        ]);
        if($validator->fails()){
            return response($validator->errors()->getMessages(),422);
        }
        $payment               = PaymentMethod::findorFail($id);
        $payment->payment_name = $request->paymentName;
        $payment->description  = $request->description;
        $payment->show         = $request->show;
        $payment->updated_at   = new DateTime;
        $payment->save();
        $res = [
            'type'    => 'success',
            'message' => 'Updated payment method successfully.',
            'data'    => new PaymentMethodResource($payment)
        ];
        return response($res, 200);
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
            PaymentMethod::destroy($id);
            $res = [
                'type'    => 'success',
                'message' => 'The payment method has been deleted',
                'data'    => []
            ];
            return response($res, 204);
        } catch(Exception $e) {
            $res = [
                'type'    => 'error',
                'message' => 'Problem deleting the payment method',
                'data'    => []
            ];
            return response($res, 500);
        }
    }
}
