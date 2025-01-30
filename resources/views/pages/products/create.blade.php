@extends('layouts.app')

@section('content')
  <div class="row mt--6">
    <div class="col-xs-12 col-lg-4 offset-lg-4">
      <div class="card">
        <!-- Form -->
        <div class="card-body">
          <!-- Data -->
          <div class="input-group form-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <input id="name" type="text" class="form-control" placeholder="{{ __('Nombre') }}" />
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
    <script src="{{ asset('js/category.js') }}"></script>
  @endsection
@endsection
