@extends('layouts.app')

@section('content')
  <div class="row mt--6">
    <div class="col-xs-12 col-lg-10 offset-lg-1">
      <div class="card">
        <!-- Form -->
        <div class="card-body row">
          <!-- Data -->
          <div class="input-group form-group col-lg-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <input id="code" type="text" class="form-control" placeholder="{{ __('CÓDIGO') }}" />
          </div>
          <div class="input-group form-group col-lg-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <select id="category" class="form-control">
              <option value="0">SELECCIONE CATEGORÍA</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="input-group form-group col-lg-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <select id="type" class="form-control">
              <option value="{{ config('constants.PRODUCT_TYPE_WHOLE') }}">
                {{ config('constants.PRODUCT_TYPE_WHOLE_UPPER') }}
              </option>
              <option value="{{ config('constants.PRODUCT_TYPE_FRACTIONAL') }}">
                {{ config('constants.PRODUCT_TYPE_FRACTIONAL_UPPER') }}
              </option>
            </select>
          </div>
          <div class="input-group form-group col-lg-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <select id="unit" class="form-control">
              <option value="0">SELECCIONE UNIDAD</option>
              @foreach ($units as $unit)
                <option value="{{ $unit->id }}">{{ $unit->abbreviation }}</option>
              @endforeach
            </select>
          </div>
          <div class="input-group form-group col-lg-9">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <input id="name" type="text" class="form-control" placeholder="{{ __('NOMBRE') }}" />
          </div>
          <div class="input-group form-group col-lg-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <input id="brand" type="text" class="form-control" placeholder="{{ __('MARCA') }}" />
          </div>
          <div class="input-group form-group col-lg-2">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <input id="priceTotal" type="number" class="form-control" placeholder="{{ __('PRECIO TOTAL') }}" />
          </div>
          <div class="input-group form-group col-lg-2">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <input id="price" type="number" class="form-control" placeholder="{{ __('PRECIO UNIDAD') }}" />
          </div>
          <div class="input-group form-group col-lg-2">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <input id="priceSale" type="number" class="form-control" placeholder="{{ __('PRECIO VENTA UNIDAD') }}" />
          </div>
        </div>
        <div class="card-footer text-right">
          <button type="button" class="btn btn-primary botones-expand" onclick="register()">
            {{ __('Registrar') }}
          </button>
        </div>
      </div>
    </div>
  </div>

  @section('js')
    <script src="{{ asset('js/material.js') }}"></script>
  @endsection
@endsection
