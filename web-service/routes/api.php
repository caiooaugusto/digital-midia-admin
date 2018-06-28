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

Route::group(array('prefix' => ''), function()
{
    Route::get('/', function () {
        return response()->json(['message' => 'Task list API', 'status' => 'Connected']);
    });

    Route::post('incident/create', 'TaskController@create')->name('task.create');
    Route::get('incident/get', 'TaskController@get')->name('task.get');
    Route::get('incident/getByRfid', 'TaskController@getbyrfid')->name('task.getbyrfid');
    Route::put('incident/update', 'TaskController@update')->name('task.update');
    Route::post('incident/delete', 'TaskController@delete')->name('task.delete');

    Route::post('panel/create', 'TaskController@create')->name('task.create');
    Route::get('panel/get', 'TaskController@get')->name('task.get');
    Route::get('panel/getByRfid', 'TaskController@getbyrfid')->name('task.getbyrfid');
    Route::put('panel/update', 'TaskController@update')->name('task.update');
    Route::post('panel/delete', 'TaskController@delete')->name('task.delete');

    Route::get('user/syncUsers', 'TaskController@syncUsers')->name('task.syncusers');
});

Route::get('/', function () {
    return redirect('api');
});

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
