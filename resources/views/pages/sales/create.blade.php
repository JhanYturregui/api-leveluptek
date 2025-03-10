@extends('layouts.app')

@section('css')
<link href="{{ asset('css/shopping-cart.css') }}" rel="stylesheet" />
@endsection

@section('content')
<div class="row mt--6">
    <div class="col-xs-12 col-lg-12">
        <div class="card">
            <!-- Form -->
            <div class="card-body row cart-shopping-container">
                <div class="cart-container col-lg-8">
                    <section class="row p-2">
                        <div class="input-group form-group col-lg-3">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                </div>
                                <input id="productCode" type="text" class="form-control" placeholder="{{ __('Código producto') }}" />
                                <div class="input-group-append" title="Haga clic para ver el listado de productos">
                                    <button class="btn btn-primary" onclick="getProductsForTransactions()"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="input-group form-group col-lg-3">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input
                                    id="documentNumberCustomer"
                                    type="text"
                                    class="form-control"
                                    placeholder="{{ __('Doc. Cliente') }}" />
                                <div class="input-group-append" title="Haga clic para ver el listado de clientes">
                                    <button class="btn btn-primary" onclick="getCustomersForTransactions()"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="input-group form-group col-lg-6">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="hidden" id="idCustomer">
                                <input
                                    id="fullNameCustomer"
                                    type="text"
                                    class="form-control"
                                    placeholder="{{ __('Nombre Cliente') }}"
                                    readonly />
                                <div class="input-group-append" id="btnRemoveCustomer" style="display: none">
                                    <button class="btn btn-primary"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 offset-lg-6 pl-5 mt-2 total-amount">
                            <label for="">
                                A cuenta: 
                                <input 
                                    type="number" 
                                    id="partialPayment"
                                    class="form-control ml-2" 
                                    value="0.00" 
                                    readonly 
                                    style="width: 110px !important" >
                            </label>
                        </div>
                        <div class="col-lg-3 pl-5 mt-2 total-amount">
                            <label for="">
                                TOTAL: <span id="textTotalAmount">S/. 0.00</span>
                            </label>
                        </div>
                    </section>
                    <form method="POST" id="formRegister" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="pl-2 pr-2 cart-details">
                            <table class="table table-bordered">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyShoppingCart"></tbody>
                            </table>
                        </div>
                </div>

                <div class="summary col-lg-4 row">
                    <div class="col-lg-6">
                        <div class="input-group form-group">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend" title="{{ __('Forma de pago') }}">
                                    <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                                </div>
                                <select class="form-control" name="saleType" id="saleType" onchange="changeSaleType(this.value)">
                                    @foreach (config('constants.SALE_TYPES') as $saleType)
                                        <option value="{{ $saleType }}">{{ $saleType }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="input-group form-group col-lg-6">
                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend" title="{{ __('Método de pago') }}">
                                <span class="input-group-text"><i class="fas fa-money-bill"></i></span>
                            </div>
                            <select id="paymentMethod" name="paymentMethod" class="form-control">
                                @foreach (config('constants.PAYMENT_METHODS') as $paymentMethod)
                                    <option value="{{ $paymentMethod }}">{{ $paymentMethod }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12 favorites-container">
                        <section class="favorites-container-products" id="containerProductsByCategory">
                            @foreach ($favorites as $product)
                            <span 
                                class="badge badge-pill badge-info p-2" 
                                onclick="addProduct({{ json_encode(['id' => $product->id, 'name' => $product->name, 'quantity' => 1, 'prices' => $product->sales_prices, 'price' => $product->sales_prices[0], 'stock' => $product->stock]) }})">{{ $product->name }}
                            </span>
                            @endforeach
                        </section>
                        <section class="favorites-container-categories">
                            <span class="badge badge-pill badge-primary p-3 mb-1" id="spanCategory-0" onclick="getProductsByCategory(0)">{{ __('Favoritos') }}</span>
                            @foreach ($categories as $category)
                            <span class="badge badge-pill badge-dark p-3 mb-1" id="spanCategory-{{ $category->id}}" onclick="getProductsByCategory({{ $category->id }})">{{ $category->name }}</span>
                            @endforeach
                        </section>
                    </div>
                    <div class="col-lg-12 mt-4">
                        <button type="submit" class="btn btn-primary btn-block" id="btnRegister">
                            {{ __('Registrar') }}
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Products -->
<div
    class="modal fade"
    id="modalProducts"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalProductsLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalProductsLabel">{{ __('Listado de productos') }}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="productsTable" class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" data-sort="name">{{ __('CÓDIGO') }}</th>
                            <th scope="col" class="sort" data-sort="name">{{ __('PRODUCTO') }}</th>
                            <th scope="col" class="sort" data-sort="name">{{ __('SELECCIONAR') }}</th>
                        </tr>
                    </thead>
                    <tbody class="list"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Customers -->
<div
    class="modal fade"
    id="modalCustomers"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalCustomersLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalCustomersLabel">{{ __('Listado de productos') }}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="customersTable" class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" data-sort="name">{{ __('DNI / RUC') }}</th>
                            <th scope="col" class="sort" data-sort="name">{{ __('NOMBRE') }}</th>
                            <th scope="col" class="sort" data-sort="name">{{ __('SELECCIONAR') }}</th>
                        </tr>
                    </thead>
                    <tbody class="list"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('js')
<script src="{{ asset('js/sale.js') }}"></script>
@endsection
@endsection