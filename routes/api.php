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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api'], function () {
    
    Route::post('/login', 'UserController@login');
    Route::post('/register', 'UserController@register');
    Route::get('/products', 'ProductController@index');
    Route::get('/products/{product}', 'ProductController@product');
    Route::get('/categories', 'CategoryController@categories');
    Route::get('/categories/{category}', 'CategoryController@category');
});

Route::group(['namespace' => 'Api', 'middleware' => 'auth:api'], function () {
    
    Route::post('/logout','UserController@logout' );

});
