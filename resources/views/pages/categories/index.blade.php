@extends('layouts.app')

@section('content')
  <div class="row mt--6">
    <div class="col">
      <div class="card">
        <div class="table-responsive p-4">
          <table id="mainTable" class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th scope="col" class="sort" data-sort="name">{{ __('ID') }}</th>
                <th scope="col" class="sort" data-sort="name">{{ __('NOMBRE') }}</th>
                <th scope="col" class="sort" data-sort="name">{{ __('FECHA CREACIÓN') }}</th>
                <th scope="col" class="sort" data-sort="name">{{ __('FECHA ACTUALIZACIÓN') }}</th>
                <th scope="col"><i class="fas fa-wrench"></i></th>
              </tr>
            </thead>
            <tbody class="list"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Delete -->
  <div
    class="modal fade"
    id="modalDelete"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalDeleteLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="modalDeleteLabel">{{ __('Eliminar') }}</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="idDataDelete" value="" />
          <p>{{ __('¿Deseas eliminar esta categoría?') }}</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancelar') }}</button>
          <button type="button" class="btn btn-primary" onclick="remove()">{{ __('Confirmar') }}</button>
        </div>
      </div>
    </div>
  </div>

  @section('js')
    <script src="{{ asset('js/category.js') }}"></script>
  @endsection
@endsection
