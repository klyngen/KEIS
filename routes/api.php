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
Route::post('equipment', 'EquipmentController@store');
Route::put('equipment/{id}', 'EquipmentController@update');
Route::delete('equipment/{id}', 'EquipmentController@delete');
Route::get('equipment/{id}/instance', 'InstanceController@getAllEquipment');

// Get all instances of equipment

Route::post('instance', 'InstanceController@store');
Route::put('instance/{id}', 'InstanceController@update');





// Instance related reltated routes
