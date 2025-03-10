@extends('layouts.app')

@section('content')
<div class="row mt--6">
    <div class="col-xs-12 col-lg-8 offset-lg-2">
        <div class="card">
            <!-- Form -->
            <div class="card-body row">
                <!-- Data -->
                <div class="input-group form-group col-lg-4">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                        </div>
                        <input
                            id="documentNumber"
                            type="text"
                            class="form-control"
                            placeholder="{{ __('Número documento') }}" />
                    </div>
                </div>
                <div class="input-group form-group col-lg-8">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                        </div>
                        <input
                            id="businessName"
                            type="text"
                            class="form-control"
                            placeholder="{{ __('Nombre / Razón social') }}" />
                    </div>
                </div>
                <div class="input-group form-group col-lg-4">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                        </div>
                        <input
                            id="phone"
                            type="text"
                            class="form-control"
                            placeholder="{{ __('Teléfono') }}" />
                    </div>
                </div>
                <div class="input-group form-group col-lg-8">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                        </div>
                        <input
                            id="address"
                            type="text"
                            class="form-control"
                            placeholder="{{ __('Dirección') }}" />
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

@section('js')
<script src="{{ asset('js/supplier.js') }}"></script>
@endsection
@endsection