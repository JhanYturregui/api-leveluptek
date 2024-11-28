@extends('layouts.app')

@section('content')
  <div class="row mt--6">
    <div class="col-xs-12 col-lg-10 offset-lg-1">
      <div class="card">
        <!-- Form -->
        <div class="card-body row">
          <!-- Data -->
          <input type="hidden" id="idData" value="{{ $material->id }}" />
          <div class="input-group form-group col-lg-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <input
              id="code"
              type="text"
              class="form-control"
              placeholder="{{ __('CÓDIGO') }}"
              value="{{ $material->code }}"
            />
          </div>
          <div class="input-group form-group col-lg-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <select id="category">
              <option value="0">SELECCIONE CATEGORÍA</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ $material->id_category == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="input-group form-group col-lg-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <select id="type" class="form-control">
              <option
                value="{{ config('constants.PRODUCT_TYPE_WHOLE') }}"
                {{ $material->type == config('constants.PRODUCT_TYPE_WHOLE' ? 'selected' : '') }}
              >
                {{ config('constants.PRODUCT_TYPE_WHOLE_UPPER') }}
              </option>
              <option
                value="{{ config('constants.PRODUCT_TYPE_FRACTIONAL') }}"
                {{ $material->type == config('constants.PRODUCT_TYPE_FRACTIONAL' ? 'selected' : '') }}
              >
                {{ config('constants.PRODUCT_TYPE_FRACTIONAL_UPPER') }}
              </option>
            </select>
          </div>
          <div class="input-group form-group col-lg-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <select id="unit">
              <option value="0">SELECCIONE UNIDAD</option>
              @foreach ($units as $unit)
                <option value="{{ $unit->id }}" {{ $material->id_unit == $unit->id ? 'selected' : '' }}>
                  {{ $unit->abbreviation }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="input-group form-group col-lg-9">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <input
              id="name"
              type="text"
              class="form-control"
              placeholder="{{ __('NOMBRE') }}"
              value="{{ $material->name }}"
            />
          </div>
          <div class="input-group form-group col-lg-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <input
              id="brand"
              type="text"
              class="form-control"
              placeholder="{{ __('MARCA') }}"
              value="{{ $material->brand }}"
            />
          </div>
          <div class="input-group form-group col-lg-2">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <input
              id="priceTotal"
              type="number"
              class="form-control"
              placeholder="{{ __('PRECIO TOTAL') }}"
              value="{{ $material->price_total }}"
            />
          </div>
          <div class="input-group form-group col-lg-2">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <input
              id="price"
              type="number"
              class="form-control"
              placeholder="{{ __('PRECIO UNIDAD') }}"
              value="{{ $material->price }}"
            />
          </div>
          <div class="input-group form-group col-lg-2">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <input
              id="priceSale"
              type="number"
              class="form-control"
              placeholder="{{ __('PRECIO VENTA UNIDAD') }}"
              value="{{ $material->price_sale }}"
            />
          </div>
        </div>
        <div class="card-footer">
          <button type="button" class="btn btn-primary botones-expand" onclick="update()">
            {{ __('Actualizar') }}
          </button>
        </div>
      </div>
    </div>
  </div>

  @section('js')
    <script src="{{ asset('js/material.js') }}"></script>
  @endsection
@endsection
