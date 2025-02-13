@extends('layouts.app')

@section('content')
<div class="row mt--6">
    <div class="col">
        <div class="card">
            <div class="table-responsive p-4">
                <input type="hidden" id="cashSessionId" value="{{ $cashSessionId }}" />
                <table id="mainTable" class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" data-sort="name">{{ __('ID') }}</th>
                            <th scope="col" class="sort" data-sort="name">{{ __('PROVEEDOR') }}</th>
                            <th scope="col" class="sort" data-sort="name">{{ __('TOTAL') }}</th>
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

<!-- Modal Register Cash Session -->
<div
    class="modal fade"
    id="modalRegisterCashSession"
    tabindex="-1"
    role="dialog"
    data-backdrop="static"
    aria-labelledby="modalRegisterCashSessionLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalRegisterCashSessionLabel">{{ __('Aperturar Caja') }}</h3>
            </div>
            <div class="modal-body row">
                <div class="input-group form-group col-lg-6">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                        </div>
                        <input
                            id="date"
                            type="date"
                            class="form-control"
                            disabled
                            placeholder="{{ __('Fecha') }}"
                            value="{{ date('Y-m-d') }}" />
                    </div>
                </div>
                <div class="input-group form-group col-lg-6">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                        </div>
                        <input
                            id="openingAmount"
                            type="number"
                            class="form-control"
                            placeholder="{{ __('Monto de apertura') }}" />
                    </div>
                </div>
                <div class="input-group form-group col-lg-12">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                        </div>
                        <textarea
                            id="comment"
                            class="form-control"
                            cols="30"
                            rows="3"
                            placeholder="{{ __('Comentario') }}"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="registerCashSession()">{{ __('Confirmar') }}</button>
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
    aria-hidden="true">
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
                <p>{{ __('¿Deseas eliminar esta compra?') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancelar') }}</button>
                <button type="button" class="btn btn-primary" onclick="remove()">{{ __('Confirmar') }}</button>
            </div>
        </div>
    </div>
</div>

@section('js')
<script src="{{ asset('js/purchase.js') }}"></script>
@endsection
@endsection