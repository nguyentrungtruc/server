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
Route::group(['namespace' => 'Admin'], function ($router) {

	//ACTIVITY
	Route::group(['prefix' => 'Activity'], function() {
		Route::get('Fetch', 'ActivityController@index');
		Route::post('/Add', 'ActivityController@store');
	    Route::post('/{id}/Edit', 'ActivityController@update');
	    Route::post('/{id}/Remove', 'ActivityController@destroy');
	});

	//CREDENTIALS
	Route::group(['prefix' => 'Credentials'], function () {
		Route::post('Login', 'AuthController@login');
		Route::post('Logout', 'AuthController@logout');
		// Route::post('Refresh', 'AuthController@refresh');
		Route::post('Dofuu', 'AuthController@me');
	});

	//CATALOGUE
	Route::group(['prefix' => 'Catalogue'], function() {
		Route::get('Fetch', 'CatalogueController@index');
		Route::post('/Add', 'CatalogueController@store');
	    Route::post('/{id}/Edit', 'CatalogueController@update');
	    Route::post('/{id}/Remove', 'CatalogueController@destroy');
	});

	//CITY
	Route::group(['prefix' => 'City'], function () {
	    Route::get('Fetch', 'CityController@index');
	    Route::post('/Add', 'CityController@store');
	    Route::post('/{id}/Edit', 'CityController@update');
	    Route::post('/{id}/Remove', 'CityController@destroy');
	});
	
	//COUNTRY
	Route::group(['prefix' => 'Country'], function () {
	    Route::get('Fetch', 'CountryController@index');
	    Route::post('/Add', 'CountryController@store');
	    Route::post('/{id}/Edit', 'CountryController@update');
	    Route::post('/{id}/Remove', 'CountryController@destroy');
	});

	//COUPON STATUS
	Route::group(['prefix' => 'CouponStatus'], function() {
		Route::get('Fetch', 'CouponStatusController@index');
		Route::post('/Add', 'CouponStatusController@store');
	    Route::post('/{id}/Edit', 'CouponStatusController@update');
	    Route::post('/{id}/Remove', 'CouponStatusController@destroy');
	});

	//DISTRICT
	Route::group(['prefix' => 'District'], function () {
	    Route::get('Fetch', 'DistrictController@index');
	    Route::get('/{cityId}/GetByCity', 'DistrictController@getByCity');
	    Route::post('/Add', 'DistrictController@store');
	    Route::post('/{id}/Edit', 'DistrictController@update');
	    Route::post('/{id}/Remove', 'DistrictController@destroy');
	});

	//ORDER STATUS
	Route::group(['prefix' => 'OrderStatus'], function() {
		Route::get('Fetch', 'OrderStatusController@index');
		Route::post('/Add', 'OrderStatusController@store');
	    Route::post('/{id}/Edit', 'OrderStatusController@update');
	    Route::post('/{id}/Remove', 'OrderStatusController@destroy');
	});

	//PRODUCT
	Route::group(['prefix' => 'Product'], function() {
		Route::get('Fetch', 'ProductController@index');
		Route::post('/Add', 'ProductController@store');
	    Route::post('/{id}/Edit', 'ProductController@update');
		Route::post('/{id}/Remove', 'ProductController@destroy');
		Route::post('/{id}/Avatar/Update', 'ProductController@updateAvatar');
	});

	//PRODUCT STATUS
	Route::group(['prefix' => 'ProductStatus'], function() {
		Route::get('Fetch', 'ProductStatusController@index');
		Route::post('/Add', 'ProductStatusController@store');
	    Route::post('/{id}/Edit', 'ProductStatusController@update');
	    Route::post('/{id}/Remove', 'ProductStatusController@destroy');
	});

	//RATING TYPE
	Route::group(['prefix' => 'RatingType'], function() {
		Route::get('Fetch', 'RatingTypeController@index');
		Route::post('/Add', 'RatingTypeController@store');
	    Route::post('/{id}/Edit', 'RatingTypeController@update');
	    Route::post('/{id}/Remove', 'RatingTypeController@destroy');
	});

	//ROLE
	Route::group(['prefix' => 'Role'], function() {
		Route::get('Fetch', 'RoleController@index');
		Route::post('/Add', 'RoleController@store');
	    Route::post('/{id}/Edit', 'RoleController@update');
	    Route::post('/{id}/Remove', 'RoleController@destroy');
	});

	//SIZE
	Route::group(['prefix' => 'Size'], function() {
		Route::get('Fetch', 'SizeController@index');
		Route::post('/Add', 'SizeController@store');
	    Route::post('/{id}/Edit', 'SizeController@update');
	    Route::post('/{id}/Remove', 'SizeController@destroy');
	});

	//STORE
	Route::group(['prefix' => 'Store'], function() {
		Route::get('Fetch', 'StoreController@index');
		Route::post('/Add', 'StoreController@store');
		Route::get('/{id}/Show', 'StoreController@show');
	    Route::post('/{id}/Edit', 'StoreController@update');
	    Route::post('/{id}/Activity/Update', 'StoreController@updateActivity');
		Route::post('/{id}/Remove', 'StoreController@destroy');
		Route::post('/{id}/Avatar/Update', 'StoreController@updateAvatar');
	});

	//STORE STATUS
	Route::group(['prefix' => 'StoreStatus'], function() {
		Route::get('Fetch', 'StoreStatusController@index');
		Route::post('/Add', 'StoreStatusController@store');
	    Route::post('/{id}/Edit', 'StoreStatusController@update');
	    Route::post('/{id}/Remove', 'StoreStatusController@destroy');
	});

	//TOPPING
	Route::group(['prefix' => 'Topping'], function() {
		Route::get('Fetch', 'ToppingController@index');
		Route::post('/Add', 'ToppingController@store');
	    Route::post('/{id}/Edit', 'ToppingController@update');
	    Route::post('/{id}/Remove', 'ToppingController@destroy');
	});

	//TYPE
	Route::group(['prefix' => 'Type'], function() {
		Route::get('Fetch', 'TypeController@index');
		Route::post('/Add', 'TypeController@store');
	    Route::post('/{id}/Edit', 'TypeController@update');
	    Route::post('/{id}/Remove', 'TypeController@destroy');
	});

	//USER
	Route::group(['prefix' => 'User'], function() {
		Route::get('Fetch', 'UserController@index');
		Route::post('/Add', 'UserController@store');
	    Route::post('/{id}/Edit', 'UserController@update');
	    Route::post('/{id}/Remove', 'UserController@destroy');
	});


	

	

	

	

	

	
	

	

	

	

	

	

});

