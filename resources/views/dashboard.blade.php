@extends('layouts.app')

<style>
    .balance {
        background: #cfd8dc;
    }
</style>

@section('content')
<input type="hidden" id="hasCashSession" value="{{ auth()->user()->has_cash_session }}" />

@if ($cashSessionId > 0)
<div class="row mt-5">
    <div class="col-xl-8 mb-5 mb-xl-0">
        <div class="card shadow">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col-6">
                        <h3 class="mb-0">Balance del día</h3>
                    </div>
                    @if (auth()->user()->role === config('constants.USER_ROLE_ADMIN'))
                    <div class="col-6 text-right">
                        <h4 class="mb-0">Vendedor: <span>{{ $cashSessionUser->name }}</span></h4>
                    </div>
                    @endif
                </div>
            </div>
            <div class="table-responsive">
                <!-- Projects table -->
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Tipo</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cashSessionData as $key => $data)
                        @if ($key == 'balance')
                        <tr class="balance">
                            <th scope="row"></th>
                            <td>{{ $data['title'] }}</td>
                            <td>
                                <i class="{{ $data['icon'] }}"></i>
                                {{ $data['total'] }}
                            </td>
                        </tr>
                        @else
                        <tr>
                            <th scope="row">{{ $data['title'] }}</th>
                            <td>{{ $data['count'] }}</td>
                            <td>
                                <i class="{{ $data['icon'] }}"></i>
                                {{ $data['total'] }}
                            </td>
                        </tr>
                        @endif
                        @endforeach

                        <tr>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card shadow">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">Cierre de caja (Efectivo)</h3>
                    </div>
                    @if (auth()->user()->id === $cashSessionUser->id)
                    <div class="col text-right">
                        <button class="btn btn-sm btn-primary" onclick="modalCloseCashSession()">Cerrar Caja</button>
                    </div>
                    @endif
                </div>
            </div>
            <div class="table-responsive">
                <!-- Projects table -->
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Tipo</th>
                            <th scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cashSessionInRegisterData as $key => $data)
                        <tr class="{{ $key === 'balance' ? 'balance' : '' }}">
                            @if ($key === 'balance')
                            <input type="hidden" id="totalInRegiser" value="{{ $data['total'] }}">
                            @endif
                            <th scope="row">{{ $data['title'] }}</th>
                            <td><i class="{{ $data['icon'] }}"></i>{{ $data['total'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@else
<div class="row pt-2">
    <div class="col-xl-4 offset-xl-4">
        <div class="card shadow">
            <div class="card-header bg-transparent">
                <div class="row align-items-center">
                    <div class="col">
                        {{-- <h6 class="text-uppercase text-muted ls-1 mb-1">Performance</h6> --}}
                        <h2 class="mb-0">{{ __('Caja') }}</h2>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p style="font-weight: 500; font-size: 0.9em">Aperture caja para poder realizar transacciones y ver
                    resumen del día</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('sales') }}" class="btn btn-primary">Aperturar caja</a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal Close Cash Session -->
<div class="modal fade" id="modalCloseCashSession" tabindex="-1" role="dialog"
    aria-labelledby="modalCloseCashSessionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalDeleteLabel">{{ __('Cerrar caja') }}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ __('¿Deseas cerrar caja?') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="closeCashSession()">{{ __('Confirmar')
                    }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancelar') }}</button>
            </div>
        </div>
    </div>
</div>

@section('js')
<script src="{{ asset('js/dashboard.js') }}"></script>
@endsection

@include('layouts.footers.auth')
@endsection

@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush