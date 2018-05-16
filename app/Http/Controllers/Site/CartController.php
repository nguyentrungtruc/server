<?php

namespace App\Http\Controllers\Site;
use Symfony\Component\HttpFoundation\Session\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CheckoutNotification;
use App\Http\Resources\Site\CouponResource;
use App\Http\Resources\Site\OrderResource;
use App\Models\Product;
use App\Models\Store;
use App\Models\Coupon;
use App\Models\RegularOrder;
use App\Models\OrderStatus;
use App\Models\City;
use App\Models\Role;
use App\Models\User;
use DateTime;
use Carbon\Carbon;
use Cookie;
class CartController extends Controller
{

	public function __construct() {
		$this->employee = Role::where('name', 'Employee')->first();
		$this->shipper  = Role::where('name', 'Shipper')->first();
		$this->status   = OrderStatus::where('order_status_name', 'Hủy')->first();
		$this->middleware('auth:api');
	}

	public function cancelOrder(Request $request) {
		$orderId = $request->id;
		$userId  = $request->owner['id'];
		$storeId = $request->store['id'];
		if(auth('api')->user()->id == $userId) {
			$order = RegularOrder::where(function($query) use ($orderId, $userId, $storeId) {

				$query->where('id', '=', $orderId);
				$query->where('user_id', '=', $userId);
				$query->where('status_id', '!=', $this->status->id);
				$query->where('store_id', '=', $storeId);

			})->first();
			if(!is_null($order)) {

				$order->status_id = $this->status->id;
				$order->note      = serialize($request->orderNotes);
				$order->save();
				$res = [
					'type'    => 'success',
					'message' => 'Cancelled order successfully.',
					'data'    => new OrderResource($order->load('user', 'shipper', 'employee', 'store', 'payment'))
				];
				
				return response($res, 200);
			}
		}
		$res = [
			'type'    => 'error',
			'message' => 'Something went wrong.',
			'data'    => []
		];
		return response($res, 500);
		
	}
	//GET ORDER DETAILS
	public function getOrderDetail(Request $request) {
		if($request->filled('orderId')) {
			$order = RegularOrder::where('user_id', '=', auth('api')->user()->id)->where('id', '=', $request->orderId)->first();
			$res   = [
				'type'    => 'success',
				'message' => 'Get order details successfully.',
				'data'    => new OrderResource($order->load('products', 'user', 'store', 'employee', 'shipper'))
			];	
			return response($res);
		}
	}
	//GET ORDERS 
	public function orderByFilter(Request $request) {
		$fromDate = $request->fromDate;
		$toDate   = $request->toDate;
		$status   = (int)$request->statusId;
		if($status == 0) {
			$orders  = RegularOrder::where('user_id', '=', auth('api')->user()->id)->where(function($query) use ($fromDate, $toDate) {
				$query->whereDate('created_at', '>=', $fromDate);
				$query->whereDate('created_at', '<=', $toDate);
			})->orderBy('id', 'desc')->get();
		} else {
			$orders  = RegularOrder::where('user_id', '=', auth('api')->user()->id)->where(function($query) use ($fromDate, $toDate, $status) {
				$query->whereDate('created_at', '>=', $fromDate);
				$query->whereDate('created_at', '<=', $toDate);
			})->where('status_id', '=', $status)->orderBy('id', 'desc')->get();
		}

		$res     = [
			'type'    => 'success',
			'message' => 'Get history information successfully.',
			'data'    => OrderResource::collection($orders->load('user', 'shipper', 'employee', 'store', 'payment'))
		];

		return response($res, 200);
	}

	//Check Coupon
	public function checkCoupon(Request $request) {
		$coupon  = $request->coupon;
		$storeID = $request->storeID;
		$now     = Carbon::now()->toDateTimeString();
		if($request->filled('cityID', 'coupon', 'districtID', 'storeID', 'subTotal' , 'deliveryPrice')) {
			$coupon = Coupon::whereHas('stores', function($query) use ($storeID) {
				$query->where('ec_stores.id', '=', $storeID);
			})->where(function($query) use ($coupon, $now) {
				$query->where('coupon', '=', $coupon);
				$query->where('actived', '=', 1);
				$query->where('status_id', '=', 1);
				$query->where('started_at', '<=', $now);
				$query->where('ended_at', '>=', $now);
			})->first();
			if(!is_null($coupon)) {
				$res = [
					'type'      => 'success',
					'message'   => 'Check coupon successfully!!!',
					'data'		=> new CouponResource($coupon),
					'secret'    => $coupon->token
				];
				return response($res, 200)->withCookie(cookie('flag_c', $request->cityID, 43200, '/', '', '', false));
			}
		}
		$res = [
			'type'      => 'error',
			'message'   => 'Coupon đã hết hạn sử dụng!!!',
			'data'		=> []
		];
		return response($res, 200)->withCookie(cookie('flag_c', $request->cityID, 43200, '/', '', '', false));
	}
	//Check out
	public function checkOut(Request $request) {
		$now = Carbon::now()->toDateTimeString();
		if($request->filled('confirmed', 'name', 'phone', 'address', 'date', 'time', 'memo', 'total', 'subTotal', 'userId', 'paymentMethod', 'distance','items', 'city', 'store')) {

			//Check confirmed
			if($request->confirmed) {
				$CID    = $request->city['id'];
				$DID    = $request->store['districtId'];
				$secret = $request->secret;
				//Find store has truly
				$store  = Store::whereHas('district', function($query) use ($DID, $CID) {
					$query->where('id', '=', $DID)->where('city_id', '=', $CID);
				})->where('id', '=', $request->store['id'])->first();
				if(!is_null($store) && auth('api')->user()->id  == $request->userId) {
					$order                  = new RegularOrder;
					$order->name            = $request->name;
					$order->address         = $request->address;
					$order->lat             = $request->lat;
					$order->lng             = $request->lng;
					$order->distance        = $request->distance;
					$order->phone           = $request->phone;
					$order->date            = $request->date;
					$order->time            = $request->time;
					$order->delivery_price  = $this->deliveryPrice($request->distance, $CID);
					$order->subtotal_amount = $this->subTotal($request->items);
					if($request->filled('secret')) {
						$coupon = Coupon::whereHas('stores', function($query) use ($store) {
							$query->where('ec_stores.id', '=', $store->id);
						})->where(function($query) use ($secret, $now) {
							$query->where('token', '=', $secret);
							$query->where('actived', '=', 1);
							$query->where('status_id', '=', 1);
							$query->where('started_at', '<=', $now);
							$query->where('ended_at', '>=', $now);
						})->first();

						if(!is_null($coupon)) {

							$order->coupon         = $coupon->coupon;
							$order->secret         = $coupon->token;
							$order->discount_total = $this->discountTotal($order->subtotal_amount, $order->delivery_price, $coupon->discount_percent);
							$order->amount         = $this->total($order->subtotal_amount, $order->delivery_price, $coupon->discount_percent);

						} else {
							$order->amount 	= $this->total($order->subtotal_amount, $order->delivery_price);
						}	
						
					} else {
						$order->amount = $this->total($order->subtotal_amount, $order->delivery_price);
					}
					$order->memo            = $request->memo;
					$order->payment_id      = $request->paymentMethod;
					$order->status_id       = 1;
					$order->user_id         = $request->userId;
					$order->store_id        = $request->store['id'];
					$order->created_at 		= new DateTime;

					$order->save();

					foreach($request->items as $data) {
						$order->products()->attach([$data['id'] => ['product_id' => $data['id'], 'quantity' => $data['qty'], 'price' => $data['price'], 'total' => ((int)$data['qty'] * (float)$data['price']) ]]);
					}
					//Notify to employee in City
					$users = User::where('role_id', '=', $this->employee->id)->get();
					foreach($users as $user) {
						$user->notify(new CheckoutNotification($order));   
					}					
					$res = [
						'type'    => 'success',
						'message' => 'Check out cart successfully.',
						'data'    => []
					];
					return response($res, 201);
				}
			}
		}
		$res = [
			'type'    => 'error',
			'message' => 'Something went wrong',
			'data'    => []
		];
		return response('Something went wrong', 500);
	}

	//CALCULATE DELIVERY PRICE
	public function deliveryPrice($distance, $id) {
		$distance = (float)$distance;
		$city = City::with('service', 'deliveries')->where('id', '=', $id)->first();
		if($city->service->delivery_actived) {
			foreach($city->deliveries as $data) {
				if($data['from'] <= $distance && $data['to'] >= $distance && $city->service['min_range'] >= $distance) {
					return $data->pivot['price'];
				} else if($data['from'] <= $distance && $data['to'] >= $distance && $city->service['max_range'] >= $distance && $city->service['min_range'] < $distance) {
					return (float)$data->pivot['price']*(float)$distance;
				}
			}
		} else {
			return (int)0;
		}
	}
	//CALCULATE SUBTOTAL
	public function subTotal($items) {
		$items    = $items;
		$subTotal = 0;
		foreach ($items as $item) {
			$store = Store::with(['products' => function($query) use ($item){
				return $query->where('ec_products.id', '=', $item['id']);
			}])->first();
			if(count($store->products)>0) {

				$subTotal = $subTotal + ((int)$item['qty'] * (float)$store->products[0]['price']);
			}
		}
		return (float)$subTotal;
	}
	//CALCULATE TOTAL
	public function total($subTotal, $deliveryPrice, $discount = 0) {
		return ((float)$subTotal + (float)$deliveryPrice) - ((float)$subTotal + (float)$deliveryPrice)*((int)$discount/100);
	}

	//CALCULATE DEAL
	public function discountTotal($subTotal, $deliveryPrice, $discount = 0) {
		return ((float)$subTotal + (float)$deliveryPrice)*((int)$discount/100);
	}
}
