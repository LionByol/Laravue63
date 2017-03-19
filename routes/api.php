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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::group(['prefix' => 'bills', 'as' => 'bills.'], function () {

    Route::get('/', ['as' => 'bills.index', 'uses' => 'BillController@index']);
    Route::get('/total', ['as' => 'bills.index', 'uses' => 'BillController@total']);
    Route::get('/payables', ['as' => 'bills.payables', 'uses' => 'BillController@indexPayable']);
    Route::get('/receivables', ['as' => 'bills.receivables', 'uses' => 'BillController@indexReceivable']);

    Route::post('/', ['as' => 'bill.new', 'uses' => 'BillController@store']);
    Route::group(['prefix' => '{id}'], function () {

        Route::get('/', ['as' => 'bill.edit', 'uses' => 'BillController@edit']);
        Route::put('/', ['as' => 'bill.update', 'uses' => 'BillController@update']);
        Route::put('/pay', ['as' => 'bill.pay', 'uses' => 'BillController@pay']);
        Route::put('/unpay', ['as' => 'bill.unpay', 'uses' => 'BillController@unpay']);
        Route::delete('/', ['as' => 'bill.destroy', 'uses' => 'BillController@destroy']);

    });


});

