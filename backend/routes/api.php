<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
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

Route::middleware(['cors', 'json.response', 'auth:api'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {
    // our routes to be protected will go in here
    Route::post('/logout', 'Auth\ApiAuthController@logout')->name('logout.api');
});


Route::resource('task', 'App\Http\Controllers\TaskController');
Route::post('task/update/', [TaskController::class, 'update']);

Route::group(['middleware' => ['cors', 'json.response']], function () {

    Route::post('/login', 'App\Http\Controllers\Auth\ApiAuthController@login')->name('login.api');
    Route::post('/register','App\Http\Controllers\Auth\ApiAuthController@register')->name('register.api');
    Route::post('/logout', 'App\Http\Controllers\Auth\ApiAuthController@logout')->name('logout.api');

});

Route::get('users','App\Http\Controllers\Auth\ApiAuthController@getUsers')->name('getUsers.api');

