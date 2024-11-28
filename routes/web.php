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

	Route::prefix('materiales')->middleware(['check.admin.role'])->group(function () {
		Route::get('/', [App\Http\Controllers\MaterialController::class, 'index'])->name('materials');
		Route::get('/data', [App\Http\Controllers\MaterialController::class, 'getData'])->name('materials_data');
		Route::get('/crear', [App\Http\Controllers\MaterialController::class, 'create'])->name('materials_create');
		Route::post('/', [App\Http\Controllers\MaterialController::class, 'store'])->name('materials_register');
		Route::get('/editar/{id}', [App\Http\Controllers\MaterialController::class, 'edit'])->name('materials_edit');
		Route::put('/', [App\Http\Controllers\MaterialController::class, 'update'])->name('materials_update');
		Route::delete('/', [App\Http\Controllers\MaterialController::class, 'delete'])->name('materials_delete');
		Route::get('/stock', [App\Http\Controllers\MaterialController::class, 'stock'])->name('materials_stock');
		Route::post('/add-stock', [App\Http\Controllers\MaterialController::class, 'addStock'])->name('materials_add_stock');
	});

	Route::prefix('tratamientos')->middleware(['check.admin.role'])->group(function () {
		Route::get('/', [App\Http\Controllers\TreatmentController::class, 'index'])->name('treatments');
		Route::get('/data', [App\Http\Controllers\TreatmentController::class, 'getData'])->name('treatments_data');
		Route::get('/crear', [App\Http\Controllers\TreatmentController::class, 'create'])->name('treatments_create');
		Route::post('/', [App\Http\Controllers\TreatmentController::class, 'store'])->name('treatments_register');
		Route::get('/editar/{id}', [App\Http\Controllers\TreatmentController::class, 'edit'])->name('treatments_edit');
		Route::put('/', [App\Http\Controllers\TreatmentController::class, 'update'])->name('treatments_update');
		Route::delete('/', [App\Http\Controllers\TreatmentController::class, 'delete'])->name('treatments_delete');
	});

	/* Route::prefix('reservations')->group(function () {
		Route::get('/', [App\Http\Controllers\ReservationController::class, 'index'])->name('reservations');
		Route::get('/data', [App\Http\Controllers\ReservationController::class, 'getData'])->name('reservations_data');
		Route::get('/create', [App\Http\Controllers\ReservationController::class, 'create'])->name('reservations_create');
		Route::post('/', [App\Http\Controllers\ReservationController::class, 'store'])->name('reservations_register');
		Route::put('/change-state', [App\Http\Controllers\ReservationController::class, 'changeState'])->name('reservations_change_state');
	}); */
});
