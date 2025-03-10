@extends('layouts.app')

@section('content')
  <div class="row mt--6">
    <div class="col-xs-12 col-lg-6 offset-lg-3">
      <div class="card">
        <!-- Form -->
        <div class="card-body row">
          <!-- Data -->
          <input type="hidden" id="idData" value="{{ $cashTransaction->id }}" />
          <div class="input-group form-group col-lg-6">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
              </div>
              <select id="type" class="form-control" disabled>
                @foreach (config('constants.CASH_TRANSACTION_TYPES') as $type)
                  <option value="{{ $type }}" {{ $cashTransaction->type == $type ? 'selected' : '' }}>
                    {{ $type }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="input-group form-group col-lg-6">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
              </div>
              <input
                id="amount"
                type="number"
                class="form-control"
                value="{{ $cashTransaction->amount }}"
                placeholder="{{ __('Monto') }}"
              />
            </div>
          </div>
          <div class="input-group form-group col-lg-12">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
              </div>
              <input type="hidden" id="customerId" value="{{ $cashTransaction->customer_id ?? 0 }}" />
              <input
                id="customer"
                type="text"
                class="form-control"
                placeholder="{{ __('Cliente') }}"
                readonly
                value="{{ $cashTransaction->customer_id ? $cashTransaction->customer->document_number . '-' . $cashTransaction->customer->full_name : '' }}"
              />
            </div>
          </div>
          <div class="input-group form-group col-lg-12">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
              </div>
              <input
                id="description"
                type="text"
                class="form-control"
                value="{{ $cashTransaction->description }}"
                placeholder="{{ __('Descripción') }}"
              />
            </div>
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
    <script src="{{ asset('js/cash_transaction.js') }}"></script>
  @endsection
@endsection
