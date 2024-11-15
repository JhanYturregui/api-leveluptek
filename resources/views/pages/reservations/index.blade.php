@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col">
      <div class="card">
        <input type="hidden" id="userRole" value="{{ auth()->user()->role }}" />
        <input type="hidden" id="adminRole" value="{{ config('constants.USER_ROLE_ADMIN') }}" />
        <!-- Card header -->
        <div class="card-header border-0">
          <div class="row">
            <h3 class="mb-0 col-8">{{ __('Gestión de Reservas') }}</h3>

            <div class="col-4 text-right">
              @if (auth()->user()->role === config('constants.USER_ROLE_CUSTOMER'))
                <a href="{{ route('reservations_create') }}" class="btn btn-primary botones-expand">
                  <i class="fas fa-plus mr-2"></i>
                  {{ 'Registrar' }}
                </a>
              @else
                <label for="">Filtar por sala:</label>
                <select id="coworking" onchange="initDatatable('mainTable')">
                  <option value="0">TODAS</option>
                  @foreach ($coworkings as $coworking)
                    <option value="{{ $coworking->id }}">{{ $coworking->name }}</option>
                  @endforeach
                </select>
              @endif
            </div>
          </div>
        </div>

        <div class="table-responsive p-4">
          <table id="mainTable" class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th scope="col" class="sort" data-sort="name">{{ __('ID') }}</th>
                @if (auth()->user()->role === config('constants.USER_ROLE_ADMIN'))
                  <th scope="col" class="sort" data-sort="name">{{ __('Cliente') }}</th>
                @endif

                <th scope="col" class="sort" data-sort="name">{{ __('Sala') }}</th>
                <th scope="col" class="sort" data-sort="name">{{ __('Fecha Inicio') }}</th>
                <th scope="col" class="sort" data-sort="name">{{ __('Fecha Fin') }}</th>
                <th scope="col" class="sort" data-sort="name">{{ __('Estado') }}</th>
                @if (auth()->user()->role === config('constants.USER_ROLE_ADMIN'))
                  <th scope="col"><i class="fas fa-wrench"></i></th>
                @endif
              </tr>
            </thead>
            <tbody class="list"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Change State -->
  <div
    class="modal fade"
    id="modalChangeState"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalDeleteLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="modalDeleteLabel">{{ __('Cambiar estado') }}</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="idDataChangeState" value="" />
          <input type="hidden" id="newState" value="" />
          <p>{{ __('¿Deseas cambiar el estado a esta reserva?') }}</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancelar') }}</button>
          <button type="button" class="btn btn-primary" onclick="changeState()">{{ __('Confirmar') }}</button>
        </div>
      </div>
    </div>
  </div>

  @section('js')
    <script src="{{ asset('js/reservation.js') }}"></script>
  @endsection
@endsection
