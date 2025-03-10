@extends('layouts.app')

@section('content')
  <div class="row mt--6">
    <div class="col-xs-12 col-lg-6 offset-lg-3">
      <div class="card">
        <!-- Form -->
        <div class="card-body row">
          <!-- Data -->
          <div class="input-group form-group col-lg-6">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
              </div>
              <select id="type" class="form-control">
                @foreach (config('constants.CASH_TRANSACTION_TYPES') as $type)
                  <option value="{{ $type }}">{{ $type }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="input-group form-group col-lg-6">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
              </div>
              <input id="amount" type="number" class="form-control" placeholder="{{ __('Monto') }}" />
            </div>
          </div>
          <div class="input-group form-group col-lg-12">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
              </div>
              <input type="hidden" id="customerId" value="0" />
              <input id="customer" type="text" class="form-control" placeholder="{{ __('Cliente') }}" readonly />
              <div class="input-group-append">
                <button
                  class="btn btn-primary"
                  id="btnSearchCustomer"
                  onclick="getCustomersForTransactions()"
                  title="Buscar cliente"
                >
                  <i class="fas fa-search"></i>
                </button>
                <button
                  class="btn btn-primary"
                  id="btnRemoveCustomer"
                  onclick="removeCustomer()"
                  style="display: none"
                  title="Quitar cliente"
                >
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="input-group form-group col-lg-12">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
              </div>
              <input id="description" type="text" class="form-control" placeholder="{{ __('Descripción') }}" />
            </div>
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

  <!-- Modal Customers -->
  <div
    class="modal fade"
    id="modalCustomers"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalCustomersLabel"
    aria-hidden="true"
  >
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
    <script src="{{ asset('js/cash_transaction.js') }}"></script>
  @endsection
@endsection
