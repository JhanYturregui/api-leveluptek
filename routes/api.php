<?php

use App\Http\Controllers\DataController;
use App\Http\Controllers\PlanetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [App\Http\Controllers\UserController::class, 'register']);
Route::post('login', [App\Http\Controllers\UserController::class, 'login']);
Route::post('logout', [App\Http\Controllers\UserController::class, 'logout']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('planets/{id}', [App\Http\Controllers\PlanetController::class, 'findOne']);
    Route::post('planets/{id}', [App\Http\Controllers\PlanetController::class, 'store']);

    Route::get('people/{id}', [App\Http\Controllers\PeopleController::class, 'findOne']);
    Route::post('people/{id}', [App\Http\Controllers\PeopleController::class, 'store']);

    Route::get('films/{id}', [App\Http\Controllers\FilmController::class, 'findOne']);
    Route::post('films/{id}', [App\Http\Controllers\FilmController::class, 'store']);
});
