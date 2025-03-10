@extends('layouts.app')

@section('content')
<div class="row mt--6">
    <div class="col-xs-12 col-lg-8 offset-lg-2">
        <div class="card">
            <!-- Form -->
            <div class="card-body row">
                <!-- Data -->
                <input type="hidden" id="idData" value="{{ $customer->id }}" />
                <div class="input-group form-group col-lg-4">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                        </div>
                        <input id="documentNumber" type="text" class="form-control"
                            value="{{ $customer->document_number }}" placeholder="{{ __('Número documento') }}" />
                    </div>
                </div>
                <div class="input-group form-group col-lg-8">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                        </div>
                        <input id="fullName" type="text" class="form-control" value="{{ $customer->full_name }}"
                            placeholder="{{ __('Nombre Completo') }}" />
                    </div>
                </div>
                <div class="input-group form-group col-lg-4">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                        </div>
                        <input id="creditLimit" type="number" class="form-control" value="{{ $customer->credit_limit }}"
                            placeholder="{{ __('Límite de crédito') }}" />
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="button" class="btn btn-primary botones-expand" onclick="update()">
                    {{ __('Actualizar') }}
                </button>
            </div>
        </div>
    </div>
</div>

@section('js')
<script src="{{ asset('js/customer.js') }}"></script>
@endsection
@endsection