@extends('layouts.app')

@section('content')
<div class="row mt--6">
    <div class="col-xs-12 col-lg-8 offset-lg-2">
        <div class="card">
            <!-- Form -->
            <div class="card-body row">
                <!-- Data -->
                <input type="hidden" value="{{ $product->id }}" id="idData" />
                <input type="hidden" value="{{ $product->prices }}" id="prices" />
                <div class="input-group form-group col-lg-4">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                        </div>
                        <input id="code" type="text" class="form-control" placeholder="{{ __('Código') }}"
                            value="{{ $product->code }}" {{ $product->parent_id ? 'readonly' : '' }} />
                    </div>
                </div>
                <div class="input-group form-group col-lg-8">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                        </div>
                        <input id="name" type="text" class="form-control" placeholder="{{ __('Nombre') }}"
                            value="{{ $product->name }}" {{ $product->parent_id ? 'readonly' : '' }} />
                    </div>
                </div>
                <div class="input-group form-group col-lg-4">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                        </div>
                        <select id="category" class="form-control" {{ $product->parent_id ? 'disabled' : '' }}>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $category->id === $product->category_id ? 'selected'
                                : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-group form-group col-lg-3">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                        </div>
                        <input id="price" type="number" class="form-control" placeholder="{{ __('Precio') }}" />
                        <div class="input-group-append">
                            <button type="button" class="btn btn-default" onclick="addPrice()">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @if (!$product->parent_id)
                <div class="input-group form-group col-lg-2 mt-2">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" id="favorite" type="checkbox" {{ $product->favorite ?
                        'checked' : '' }} />
                        <label class="custom-control-label" for="favorite">Favorito</label>
                    </div>
                </div>
                <div class="input-group form-group col-lg-2 mt-2">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" id="hasContainer" type="checkbox" {{ $product->has_container
                        ?
                        'checked' : '' }} />
                        <label class="custom-control-label" for="hasContainer">Envase</label>
                    </div>
                </div>
                @endif

                {{-- PRECIOS --}}
                <div class="card col-lg-4 offset-lg-4 mt-4">
                    <div class="card-header">
                        <label class="form-label titles">{{ __('Listado de precios') }}</label>
                    </div>
                    <div class="card-body">
                        <div id="divPricesList"></div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="button" class="btn btn-primary botones-expand" onclick="update()">
                    {{ __('Actualizar') }}
                </button>
            </div>
        </div>
    </div>
</div>

@section('js')
<script src="{{ asset('js/product.js') }}"></script>
@endsection
@endsection