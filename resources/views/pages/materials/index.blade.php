@extends('layouts.app')

@section('content')
  <div class="row mt--6">
    <div class="col">
      <div class="card">
        <div class="table-responsive p-4">
          <table id="mainTable" class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th scope="col" class="sort" data-sort="name">{{ __('CÓDIGO') }}</th>
                <th scope="col" class="sort" data-sort="name">{{ __('CATEGORÍA') }}</th>
                <th scope="col" class="sort" data-sort="name">{{ __('TIPO') }}</th>
                <th scope="col" class="sort" data-sort="name">{{ __('MATERIAL') }}</th>
                <th scope="col" class="sort" data-sort="name">{{ __('PRESENTACIÓN') }}</th>
                <th scope="col" class="sort" data-sort="name">{{ __('MARCA') }}</th>
                <th scope="col" class="sort" data-sort="name">{{ __('PRECIO TOTAL') }}</th>
                <th scope="col" class="sort" data-sort="name">{{ __('PRECIO UNIDAD') }}</th>
                <th scope="col" class="sort" data-sort="name">{{ __('PRECIO VENTA UND') }}</th>
                <th scope="col" class="sort" data-sort="name">{{ __('FECHA CREACIÓN') }}</th>
                <th scope="col"><i class="fas fa-wrench"></i></th>
              </tr>
            </thead>
            <tbody class="list"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Inventario -->
  <div
    class="modal fade"
    id="modalStock"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalStockLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="modalStockLabel">{{ __('Inventario') }}</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="idMaterial" />
          <div class="table-responsive">
            <table id="stockTable" class="table align-items-center table-flush">
              <thead class="thead-light">
                <tr>
                  <th scope="col" class="sort" data-sort="name">{{ __('LOTE') }}</th>
                  <th scope="col" class="sort" data-sort="name">{{ __('CANTIDAD') }}</th>
                  <th scope="col" class="sort" data-sort="name">{{ __('FECHA DE VENCIMIENTO') }}</th>
                </tr>
              </thead>
              <tbody class="list"></tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary text-right" title="Agregar entrada" onclick="modalAddStock()">
            <i class="fas fa-plus"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Agregar stock -->
  <div
    class="modal fade"
    id="modalAddStock"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalAddStockLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="modalAddStockLabel">{{ __('Agregar entrada') }}</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="input-group form-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <input id="batch" type="text" class="form-control" placeholder="{{ __('Lote') }}" />
          </div>
          <div class="input-group form-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <input id="amount" type="number" class="form-control" placeholder="{{ __('Cantidad') }}" />
          </div>
          <div class="input-group form-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <input id="expirationDate" type="date" class="form-control" placeholder="{{ __('Fecha vencimiento') }}" />
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="addStock()">{{ __('Agregar') }}</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cerrar') }}</button>
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
          <p>{{ __('¿Deseas eliminar este material?') }}</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancelar') }}</button>
          <button type="button" class="btn btn-primary" onclick="remove()">{{ __('Confirmar') }}</button>
        </div>
      </div>
    </div>
  </div>

  @section('js')
    <script src="{{ asset('js/material.js') }}"></script>
  @endsection
@endsection
