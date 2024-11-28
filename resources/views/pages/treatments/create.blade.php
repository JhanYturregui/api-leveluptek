@extends('layouts.app')

@section('content')
  <div class="row mt--6">
    <div class="col-xs-12 col-lg-6 offset-lg-3">
      <div class="card">
        <!-- Form -->
        <div class="card-body row">
          <!-- Data -->
          <div class="input-group form-group col-lg-9">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <input id="name" type="text" class="form-control" placeholder="{{ __('Nombre') }}" />
          </div>
          <div class="input-group form-group col-lg-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
            </div>
            <input id="price" type="number" class="form-control" placeholder="{{ __('Precio') }}" />
          </div>
          <div class="card col-lg-10 offset-lg-1">
            <div class="card-header">
              <label class="form-label">Materiales</label>
            </div>
            <div class="card-body">
              <div class="input-group mb-2">
                <input type="text" class="form-control" id="materialId" placeholder="Código material" required />
                <button type="button" class="btn btn-default" onclick="findMaterial()">
                  <i class="fas fa-search"></i>
                </button>
                <button
                  type="button"
                  class="btn btn-primary ml-2"
                  onclick="listMaterials()"
                  title="Lista de materiales"
                >
                  <i class="fas fa-list"></i>
                </button>
              </div>
              <div id="divMaterialsList"></div>
            </div>
          </div>
        </div>
        <div class="card-footer text-center">
          <button type="button" class="btn btn-primary botones-expand" onclick="register()">
            {{ __('Registrar') }}
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Materials -->
  <div
    class="modal fade"
    id="modalMaterials"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalMaterialsLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="modalMaterialsLabel">{{ __('Seleccionar materiales') }}</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="table-responsive p-1">
            <table id="materialsTable" class="table align-items-center table-flush">
              <thead class="thead-light">
                <tr>
                  <th scope="col" class="sort" data-sort="name">{{ __('CÓDIGO') }}</th>
                  <th scope="col" class="sort" data-sort="name">{{ __('MATERIAL') }}</th>
                  <th scope="col" class="sort" data-sort="name">{{ __('SELECCIONAR') }}</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cerrar') }}</button>
        </div>
      </div>
    </div>
  </div>

  @section('js')
    <script src="{{ asset('js/treatment.js') }}"></script>
  @endsection
@endsection
