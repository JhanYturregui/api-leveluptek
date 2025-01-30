<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/', 'App\Http\Controllers\HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::prefix('categorias')->middleware(['check.admin.role'])->group(function () {
		Route::get('/', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories');
		Route::get('/data', [App\Http\Controllers\CategoryController::class, 'getData'])->name('categories_data');
		Route::get('/crear', [App\Http\Controllers\CategoryController::class, 'create'])->name('categories_create');
		Route::post('/', [App\Http\Controllers\CategoryController::class, 'store'])->name('categories_register');
		Route::get('/editar/{id}', [App\Http\Controllers\CategoryController::class, 'edit'])->name('categories_edit');
		Route::put('/', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories_update');
		Route::delete('/', [App\Http\Controllers\CategoryController::class, 'delete'])->name('categories_delete');
	});

	Route::prefix('productos')->middleware(['check.admin.role'])->group(function () {
		Route::get('/', [App\Http\Controllers\ProductController::class, 'index'])->name('products');
		Route::get('/data', [App\Http\Controllers\ProductController::class, 'getData'])->name('products_data');
		Route::get('/crear', [App\Http\Controllers\ProductController::class, 'create'])->name('products_create');
		Route::post('/', [App\Http\Controllers\ProductController::class, 'store'])->name('products_register');
		Route::get('/editar/{id}', [App\Http\Controllers\ProductController::class, 'edit'])->name('products_edit');
		Route::put('/', [App\Http\Controllers\ProductController::class, 'update'])->name('products_update');
		Route::delete('/', [App\Http\Controllers\ProductController::class, 'delete'])->name('products_delete');
	});

	/* Route::prefix('reservations')->group(function () {
		Route::get('/', [App\Http\Controllers\ReservationController::class, 'index'])->name('reservations');
		Route::get('/data', [App\Http\Controllers\ReservationController::class, 'getData'])->name('reservations_data');
		Route::get('/create', [App\Http\Controllers\ReservationController::class, 'create'])->name('reservations_create');
		Route::post('/', [App\Http\Controllers\ReservationController::class, 'store'])->name('reservations_register');
		Route::put('/change-state', [App\Http\Controllers\ReservationController::class, 'changeState'])->name('reservations_change_state');
	}); */
});
