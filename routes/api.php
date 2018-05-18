<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActiveUserMail;
use Symfony\Component\HttpFoundation\Session\Session;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */
Route::group(['middleware' => ['api'], 'prefix' => 'DOFUU-AUTH/8420be8df65a43e246a0a6215c5ed977bb099cb8'], function ($router) {
	Route::group(['namespace' => 'Admin'], function () {
	        //CREDENTIAL
		Route::post('login', 'AuthController@login');
		Route::post('logout', 'AuthController@logout');
		Route::post('refresh', 'AuthController@refresh');
		Route::post('me', 'AuthController@me');
	});
});
Route::middleware('api', 'roles')->get('/dofuuAdmin', ['roles' => 'admin', function (Request $request) {
	$user = Auth::guard('api')->user();
	return $user;
}]);

Route::get('/test', [function (Request $request) {
	$session = new Session();
	// $session->set('key', 'database');
	dd($session->all());
	// $session = new Session();
	// $session->set('key', 'value');
	// $request->session->put('key', 'abc');
	// dd($session->get('key'));
    // Mail::to('ironman@gmail.com')->send(new ActiveUserMail($user));

    // $route = $request->route()->getAction();
    // $user = User::find(100000000)->first();
    // $action = $request->route()->getAction();
    // // $date = "2018-03-31";
    // $value = Carbon\Carbon::createFromFormat('Y-m-d H:i', $date." 23:59");
    // var_dump('now:'. Carbon\Carbon::today()->timestamp);
    // var_dump($value->timestamp);

}])->middleware(['auth:api']);

Route::group(['middleware' => ['auth:api', 'roles'], 'namespace' => 'Admin'], function () {

	Route::group(['roles' => ['admin', 'employee']], function () {
		//API CHOOSE SHIPPER FOR ORDER
		Route::post('/Dofuu-Checkout/ChooseShipperForOrder', 'CheckoutController@chooseShipper');
		//API CANCEL ORDER 
		Route::post('/Dofuu-Checkout/GetOrderDetails/CancelOrder', 'CheckoutController@cancelOrder');
		//API CHANGE ORDER STATUS
		Route::post('/Dofuu-Checkout/GetOrderDetails/ChangeStatus/', 'CheckoutController@changeStatusOrder');
		//API GET ORDER DETAILS
		Route::post('/Dofuu-Checkout/GetOrderDetails/{id}', 'CheckoutController@getOrderDetails');
		//API GET ORDERS 
		Route::post('/Dofuu-Checkout/GetOrderByFilter', 'CheckoutController@getOrderByFilter');
		//API PAYMENT METHODS
		Route::resource('/Dofuu-Payment-Method', 'PaymentMethodController');
 		//API RANGE FOR DELIVERY
		Route::resource('/Dofuu-Range', 'RangeController');
		//API ORDER STATUS
		Route::resource('/Dofuu-Order-Status', 'OrderStatusController');
		//API COUPON STATUS
		Route::resource('/Dofuu-Coupon-Status', 'CouponStatusController');
		//API PRODUCT STATUS
		Route::resource('Dofuu-Product-Status', 'ProductStatusController');
        //API ACTIVITY
		Route::resource('/Dofuu-Activity', 'ActivityController');
		//API STORE STATUS
		Route::resource('/Dofuu-Store-Status', 'StoreStatusController');
        //API TYPE
		Route::resource('/Dofuu-Type', 'TypeController');
        //API DISTRICT
		Route::resource('/Dofuu-District', 'DistrictController');
		//API COUNTRY
		Route::resource('/Dofuu-Country', 'CountryController');
        //API GET SHIPPER
		Route::post('/Dofuu-User/GetShippers', 'UserController@getShipper');
        //API USER
		Route::resource('/Dofuu-User', 'UserController');
        //API ROLE
		Route::resource('Dofuu-Role', 'RoleController');
        //API CITY WITH STORE
		Route::get('/Dofuu-City/GetCityWithStore', 'CityController@cityWithStore');
        //API CITY
		Route::resource('/Dofuu-City', 'CityController', ['only' => ['index', 'store', 'update', 'destroy']]);
	});
	Route::group(['roles' => ['admin', 'employee']], function() {
		//API SERVICE
		Route::post('/Dofuu-Service/UpdateService', 'CityController@updateService');
		//API DELIVERY
		Route::resource('/Dofuu-Delivery', 'DeliveryController');
		//API COUPON
		Route::post('/Dofuu-Coupon/{id}/updateStore', 'CouponController@updateCoupon');
		Route::resource('/Dofuu-Coupon', 'CouponController', ['except' => ['updateCoupon']]);
		//API PRODUCT
		Route::get('/Dofuu-Product/{sid}', 'ProductController@index');
		Route::resource('/Dofuu-Product', 'ProductController', ['except' => ['index']]);
	       //API CATALOGUE
		Route::get('/Dofuu-Catalogue/GetCatalogue/{sid}/', 'CatalogueController@index');
		Route::resource('/Dofuu-Catalogue', 'CatalogueController', ['except' => ['index']]);
		//Update Activity
		Route::post('/DofuuActivityTime/{id}', 'StoreController@updateActivity');
		//API STORE
		Route::resource('/Dofuu-Store', 'StoreController');
		//API ACTIVE DELIVERY FOR CITY
		Route::post('/Dofuu-Cities/ActiveDelivery/{cid}', 'CityController@activeDelivery');        
		//API CITY NOT DELIVERY
		Route::get('/Dofuu-City/DoesntHaveDelivery', 'CityController@citiesDoesntHaveDelivery');
		//API READ NOTIFICATIONS 
		Route::post('/Dofuu-Notification/ReadNotification', 'NotificationController@readNotification');
		//API GET NOTIFICATIONS
		Route::post('/Dofuu-Notification/GetNotification', 'NotificationController@getNotification');
	});
});


App::setLocale('vi');
Route::group(['middleware' => 'api', 'namespace' => 'Site'], function ($router) {
	Route::group(['prefix' => 'auth'], function () {
        //CREDENTIAL
		Route::post('login', 'AuthController@login');
		Route::post('logout', 'AuthController@logout');
		Route::post('refresh', 'AuthController@refresh');
		Route::post('/df', 'AuthController@me');
		Route::group(['middleware' => ['roles'], 'roles' => ['customer', 'employee', 'partner']], function () {
		});
	});
	Route::group(['prefix' => 'Dofuu'], function() {
		//Check Out and Order By Customer
		Route::post('CheckCouponCode', 'CartController@checkCoupon');
		Route::post('CheckOut', 'CartController@checkOut');
		Route::post('OrderByFilter', 'CartController@orderByFilter');
		Route::post('Order/GetOrderDetail', 'CartController@getOrderDetail');
		Route::post('Order/CancelByCustomer', 'CartController@cancelOrder');
	});
});

Route::group(['namespace' => 'Site'], function() {
	Route::post('/register', 'AuthController@register');
	Route::post('/user/active', 'AuthController@active');
    //FETCH CITY
	Route::get('/FetchCities', 'CityController@fetchCity');
	//GET CITY CURRENT
	Route::post('/GetCityCurrent/{city}', 'CityController@getCityCurrent');
	//GET INFORMATION CITY
	Route::get('/GetCityInformation/{city}', 'CityController@getInformation');
    //GET INFORMATION CITY HAS DEAL
	Route::get('/GetCityInformationHasDeal/{city}', 'CityController@getInformationHasDeal');
    // FETCH TYPE
	Route::get('/FetchTypes', 'TypeController@index');
	// FETCH STORE BY TYPE
	Route::post('/GetStoreByType/{city}', 'StoreController@storeByType');
    //FETCH STORE WITH DEAL BY CITY ID
	Route::get('/LoadStoreHasDeal', 'StoreController@getAllStoreWithDeal');
	//FETCH STORE 
	Route::get('/LoadStore', 'StoreController@getAllStore');
	//SHOW STORE
	Route::get('/GetStore', 'StoreController@getStore');
	//ASYNC AUTOCOMPLETE SEARCH
	Route::get('Search/Bar/Query', 'StoreController@searchQuery');
	//SEARCH STORE
	Route::get('Search/All', 'StoreController@searchStore');
	//SEARCH STORE BY PLACE
	Route::get('Search/Places', 'StoreController@searchStoreByPlace');
	//SEARCH STORE BY TYPE
	Route::get('Search/Types', 'StoreController@searchStoreByType');
	//SEARCH STORE BY PRODUCT
	Route::get('Search/Products', 'StoreController@searchStoreByProduct');
});
