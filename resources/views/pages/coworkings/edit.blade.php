@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-xs-12 col-lg-4 offset-lg-4">
      <div class="card">
        <!-- Card header -->
        <div class="card-header border-0">
          <h3 class="mb-0">{{ __('Editar') }}</h3>
        </div>

        <!-- Form -->
        <div class="card-body">
          <!-- Data -->
          <input type="hidden" id="idData" value="{{ $coworking['id'] }}" />
          <div class="input-group form-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <input
              id="name"
              type="text"
              class="form-control"
              placeholder="{{ __('Nombre') }}"
              value="{{ $coworking['name'] }}"
            />
          </div>
          <div class="input-group form-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <textarea id="description" class="form-control" placeholder="{{ __('Descripción') }}">
{{ $coworking['description'] }}</textarea
            >
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
    <script src="{{ asset('js/coworking.js') }}"></script>
  @endsection
@endsection
