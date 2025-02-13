<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();


Route::group(['middleware' => 'auth'], function () {

	Route::get('/', 'App\Http\Controllers\HomeController@index')->name('home');

	Route::prefix('categorias')->group(function () {
		Route::get('/', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories');
		Route::get('/data', [App\Http\Controllers\CategoryController::class, 'getData'])->name('categories_data');
		Route::get('/crear', [App\Http\Controllers\CategoryController::class, 'create'])->name('categories_create');
		Route::post('/', [App\Http\Controllers\CategoryController::class, 'store'])->name('categories_register');
		Route::get('/editar/{id}', [App\Http\Controllers\CategoryController::class, 'edit'])->name('categories_edit');
		Route::put('/', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories_update');
		Route::delete('/', [App\Http\Controllers\CategoryController::class, 'delete'])->name('categories_delete');
	});

	Route::prefix('productos')->group(function () {
		Route::get('/', [App\Http\Controllers\ProductController::class, 'index'])->name('products');
		Route::get('/data', [App\Http\Controllers\ProductController::class, 'getData'])->name('products_data');
		Route::get('/crear', [App\Http\Controllers\ProductController::class, 'create'])->name('products_create');
		Route::post('/', [App\Http\Controllers\ProductController::class, 'store'])->name('products_register');
		Route::get('/editar/{id}', [App\Http\Controllers\ProductController::class, 'edit'])->name('products_edit');
		Route::put('/', [App\Http\Controllers\ProductController::class, 'update'])->name('products_update');
		Route::delete('/', [App\Http\Controllers\ProductController::class, 'delete'])->name('products_delete');
		Route::get('/obtener', [App\Http\Controllers\ProductController::class, 'findByCode'])->name('products_find_by_code');
		Route::get('/transacciones', [App\Http\Controllers\ProductController::class, 'getProductsForTransactions'])->name('products_data_transactions');
		Route::get('/obtener_por_categoria/{categoryId}', [App\Http\Controllers\ProductController::class, 'getByCategory'])->name('products_get_by_category');
	});

	Route::prefix('caja')->group(function () {
		Route::post('/', [App\Http\Controllers\CashSessionController::class, 'store'])->name('purchases_register');
	});

	Route::prefix('compras')->group(function () {
		Route::get('/', [App\Http\Controllers\PurchaseController::class, 'index'])->name('purchases');
		Route::get('/data', [App\Http\Controllers\PurchaseController::class, 'getData'])->name('purchases_data');
		Route::get('/crear', [App\Http\Controllers\PurchaseController::class, 'create'])->middleware('check.cash.session')->name('purchases_create');
		Route::post('/', [App\Http\Controllers\PurchaseController::class, 'store'])->middleware('check.cash.session')->name('purchases_register');
		Route::get('/editar/{id}', [App\Http\Controllers\PurchaseController::class, 'edit'])->middleware('check.cash.session')->name('purchases_edit');
		Route::put('/', [App\Http\Controllers\PurchaseController::class, 'update'])->middleware('check.cash.session')->name('purchases_update');
		Route::delete('/', [App\Http\Controllers\PurchaseController::class, 'delete'])->middleware('check.cash.session')->name('purchases_delete');
	});

	Route::prefix('proveedores')->group(function () {
		Route::get('/', [App\Http\Controllers\SupplierController::class, 'index'])->name('suppliers');
		Route::get('/data', [App\Http\Controllers\SupplierController::class, 'getData'])->name('suppliers_data');
		Route::get('/crear', [App\Http\Controllers\SupplierController::class, 'create'])->name('suppliers_create');
		Route::post('/', [App\Http\Controllers\SupplierController::class, 'store'])->name('suppliers_register');
		Route::get('/editar/{id}', [App\Http\Controllers\SupplierController::class, 'edit'])->name('suppliers_edit');
		Route::put('/', [App\Http\Controllers\SupplierController::class, 'update'])->name('suppliers_update');
		Route::delete('/', [App\Http\Controllers\SupplierController::class, 'delete'])->name('suppliers_delete');
		Route::get('/obtener', [App\Http\Controllers\SupplierController::class, 'findByDocumentNumber'])->name('suppliers_find_by_document_number');
		Route::get('/transacciones', [App\Http\Controllers\SupplierController::class, 'getSuppliersForTransactions'])->name('suppliers_data_transactions');
	});

	Route::prefix('ventas')->group(function () {
		Route::get('/', [App\Http\Controllers\SaleController::class, 'index'])->name('sales');
		Route::get('/data', [App\Http\Controllers\SaleController::class, 'getData'])->name('sales_data');
		Route::get('/crear', [App\Http\Controllers\SaleController::class, 'create'])->middleware('check.cash.session')->name('sales_create');
		Route::post('/', [App\Http\Controllers\SaleController::class, 'store'])->middleware('check.cash.session')->name('sales_register');
		Route::get('/editar/{id}', [App\Http\Controllers\SaleController::class, 'edit'])->middleware('check.cash.session')->name('sales_edit');
		Route::put('/', [App\Http\Controllers\SaleController::class, 'update'])->middleware('check.cash.session')->name('sales_update');
		Route::delete('/', [App\Http\Controllers\SaleController::class, 'delete'])->middleware('check.cash.session')->name('sales_delete');
	});

	Route::prefix('clientes')->group(function () {
		/* Route::get('/', [App\Http\Controllers\CustomerController::class, 'index'])->name('customers');
		Route::get('/data', [App\Http\Controllers\CustomerController::class, 'getData'])->name('customers_data');
		Route::get('/crear', [App\Http\Controllers\CustomerController::class, 'create'])->name('customers_create');
		Route::post('/', [App\Http\Controllers\CustomerController::class, 'store'])->name('customers_register');
		Route::get('/editar/{id}', [App\Http\Controllers\CustomerController::class, 'edit'])->name('customers_edit');
		Route::put('/', [App\Http\Controllers\CustomerController::class, 'update'])->name('customers_update');
		Route::delete('/', [App\Http\Controllers\CustomerController::class, 'delete'])->name('customers_delete'); */
		Route::get('/obtener', [App\Http\Controllers\CustomerController::class, 'findByDocumentNumber'])->name('customers_find_by_document_number');
		Route::get('/transacciones', [App\Http\Controllers\CustomerController::class, 'getCustomersForTransactions'])->name('customers_data_transactions');
	});
});
