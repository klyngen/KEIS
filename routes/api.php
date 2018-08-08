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

// Equipment Related Routes
Route::get('equipment', 'EquipmentController@index');
Route::get('equipment/{id}', 'EquipmentController@show');
Route::post('equipment/search', 'EquipmentController@search');
Route::post('equipment', 'EquipmentController@store');
Route::put('equipment/{id}', 'EquipmentController@update');
Route::delete('equipment/{id}', 'EquipmentController@delete');
Route::get('equipment/{id}/instance', 'InstanceController@getAllEquipment');

// Instance functionality
Route::post('instance/rfid', 'InstanceController@rfid');
Route::post('instance', 'InstanceController@store');
Route::put('instance/{id}', 'InstanceController@update');
Route::delete('instance', 'InstanceController@delete');

// Rent functionality
Route::get('rent', 'RentController@index');
Route::get('rent/{id}', 'RentController@show');
Route::post('rent', 'RentController@store');
Route::post('rent/deliver', 'RentController@deliver');
Route::get('stats', 'RentController@statistics');

// User functionality
Route::get('user', 'UserController@index');
Route::get('user/{id}', 'UserController@show');
Route::put('user/{id}', 'UserController@update');
Route::delete('user/{id}', 'UserController@delete');
Route::post('user', 'UserController@store');
Route::get('user/activerent/{id}', 'UserController@activeRent'); 
Route::get('user/search/{id}', 'UserController@findUserById');

// Get brands and types
Route::get('brand', 'BrandController@index');
Route::get('type', 'TypeController@index');

// Time logging API
Route::post('timeLog', 'TimeLogController@createLogInstance');
Route::put('timeLog', 'TimeLogController@updateLogEntry');
Route::post('timeLog/get', 'TimeLogController@getLogData');
Route::get('timeLog/{id}', 'TimeLogController@getLogEntry');


// Utilities
Route::post('rfid', 'InstanceController@validateRFID');
