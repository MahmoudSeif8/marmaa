<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('auth/provider/{id}', 'Auth\LoginController@redirectToProvider');
/*Socialite Login Routes*/
Route::get('auth/facebook/callback', 'Auth\LoginController@handleFacebookCallback');
Route::get('auth/google/callback', 'Auth\LoginController@handleGoogleCallback');
Route::get('auth/twitter/callback', 'Auth\LoginController@handleTwitterCallback');
/*Socialite Login Routes*/

