<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'App\Http\Controllers\HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::prefix('coworkings')->middleware(['check.admin.role'])->group(function () {
		Route::get('/', [App\Http\Controllers\CoworkingController::class, 'index'])->name('coworkings');
		Route::get('/data', [App\Http\Controllers\CoworkingController::class, 'getData'])->name('coworkings_data');
		Route::get('/create', [App\Http\Controllers\CoworkingController::class, 'create'])->name('coworkings_create');
		Route::post('/', [App\Http\Controllers\CoworkingController::class, 'store'])->name('coworkings_register');
		Route::get('/edit/{id}', [App\Http\Controllers\CoworkingController::class, 'edit'])->name('coworkings_edit');
		Route::put('/', [App\Http\Controllers\CoworkingController::class, 'update'])->name('coworkings_update');
		Route::delete('/', [App\Http\Controllers\CoworkingController::class, 'delete'])->name('coworkings_delete');
	});

	Route::prefix('reservations')->group(function () {
		Route::get('/', [App\Http\Controllers\ReservationController::class, 'index'])->name('reservations');
		Route::get('/data', [App\Http\Controllers\ReservationController::class, 'getData'])->name('reservations_data');
		Route::get('/create', [App\Http\Controllers\ReservationController::class, 'create'])->name('reservations_create');
		Route::post('/', [App\Http\Controllers\ReservationController::class, 'store'])->name('reservations_register');
		Route::put('/change-state', [App\Http\Controllers\ReservationController::class, 'changeState'])->name('reservations_change_state');
	});
});

