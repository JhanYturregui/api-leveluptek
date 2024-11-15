@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-xs-12 col-lg-6 offset-lg-3">
      <div class="card">
        <!-- Card header -->
        <div class="card-header border-0">
          <h3 class="mb-0">{{ __('Registrar') }}</h3>
        </div>

        <!-- Form -->
        <div class="card-body">
          <!-- Data -->
          <div class="input-group form-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <select id="coworking">
              @foreach ($coworkings as $coworking)
                <option value="{{ $coworking->id }}">{{ $coworking->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="input-group form-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-calendar"></i></span>
            </div>
            <input id="startDate" type="datetime-local" class="form-control" onchange="calculateEndDate(this.value)" />
          </div>
          <div class="input-group form-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-calendar"></i></span>
            </div>
            <input id="endDate" type="datetime-local" class="form-control" disabled />
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
    <script src="{{ asset('js/reservation.js') }}"></script>
  @endsection
@endsection
