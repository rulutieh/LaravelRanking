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

Route::get('print/{name}', 'TestController@print');

Route::get('score/get/{hash}', 'ScoreController@get');
Route::get('score/add/{hash}/uid/{uid}/sco/{sco}/kk/{kk}/cc/{cc}/gg/{gg}/bb/{bb}/mm/{mm}/maxcombo/{maxcombo}', 'ScoreController@add');