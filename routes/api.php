<?php

use Illuminate\Http\Request;

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

Route::group(['namespace' => 'Api' , 'middleware' => 'cors' , 'prefix' => 'v1'], function () {
    /*Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });*/
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login','AuthController@isUserToken')->name('api-auth-login-access');
        Route::post('/register', 'AuthController@newUser')->name('api.register');
        Route::post('/verify', 'AuthController@VerifyUser')->name('api.activate-user');
        /*  Route::post('/forgetPassword', 'AuthController@forgetPassword')->name('api.forget-password');
          Route::post('/activeResetCode', 'AuthController@activeResetCode')->name('api.active-reset-code');
          Route::post('/resetPassword', 'AuthController@resetPassword')->name('api-reset-password');*/
    });

    Route::get('/consumer/types', 'UserController@consumerTypes')->name('api.consumer.type');
    Route::get('/category/positions' , 'UserController@categoryPositions')->name('api-category-position');
    Route::get('/payment/costing/options' , 'UserController@PaymentCostingOptions')->name('api-costing-option');
    Route::get('/location' , 'OwnerController@location')->name('api-countries');
    Route::get('/sport/playground/feature/options' , 'OwnerController@playgroundSportFeatureOptions')->name('api-newLocation-options');

    Route::group(['prefix' =>'user', 'middleware' => 'auth:api'], function () {
        Route::post('/choose/type','UserController@chooseType')->name('api-choose-type');
        Route::get('/profile' , 'UserController@userProfile')->name('api-user-profile');
        Route::post('/profile/update' , 'UserController@updateProfile')->name('api-update-profile');
        Route::post('/add/document' , 'UserController@addDocument')->name('api-add-document');
        Route::post('/update/document' , 'UserController@updateDocument')->name('api-update-document');
        Route::get('/delete/document/{id}', 'UserController@deleteDocument')->name('api-delete-document');
        Route::post('/choose/payment/option', 'UserController@choosePaymentOption')->name('api-choose-payment');
    });

    Route::group(['prefix' =>'owner', 'middleware' => 'auth:api'], function () {
        Route::post('/store/location' , 'OwnerController@addLocation')->name('api-create-location');
        Route::get('/locations' , 'OwnerController@getOwnerLocations')->name('api-owner-locations');
        Route::post('/store/field' , 'OwnerController@addField')->name('api-create-field');
        Route::post('/upload/field/images' , 'OwnerController@fieldImages')->name('api-upload-field-images');
        Route::post('/add/user', 'AuthController@newUser')->name('api-add-owner-user');
        Route::get('/users' , 'OwnerController@ownerUsers')->name('api-owner-users');
    });
});

