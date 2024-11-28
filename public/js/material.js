let origin = '';
let scrollX = false;
const ROUTE_MODULE = 'materiales';
const MAIN_DATATABLE_PARAMS = {
  idTable: 'mainTable',
  url: `${ROUTE_MODULE}/data`,
  type: 'get',
  columns: [
      { data: 'code', searchable: false },
      { data: 'category_name', searchable: false },
      { data: 'type', searchable: false },
      { data: 'name', name: 'm.name' },
      { data: 'unit_name', name: 'u.name', searchable: true },
      { data: 'brand', searchable: false },
      { data: 'price_total', searchable: false },
      { data: 'price_sale', searchable: false },
      { data: 'price', searchable: false },
      { data: 'created_at_formatted', searchable: false },
      { data: 'col-actions', searchable: false, orderable: false },
  ],
  searching: true,
}

window.onload = function() {
  origin = window.location.origin;
  scrollX = window.screen.width <= 1240 ? true : false;
  initDatatable(MAIN_DATATABLE_PARAMS);
  
}

window.onresize = function () {
  scrollX = window.screen.width <= 1240 ? true : false;
  initDatatable(MAIN_DATATABLE_PARAMS);
}

/* function initDatatable (idTable) {
  const table = $('#' + idTable).DataTable();
  table.destroy();

  $('#' + idTable).DataTable({
    "serverSide": true,
    "processing": true,
    "responsive": true,
    "scrollX": scrollX,
    "ajax": {
      "url": `${ROUTE_MODULE}/data`,
      "type": "get"
    },
    "columns": [
        { data: 'code', searchable: false },
        { data: 'category_name', searchable: false },
        { data: 'type', searchable: false },
        { data: 'name' },
        { data: 'unit_name', searchable: false },
        { data: 'brand', searchable: false },
        { data: 'price_total', searchable: false },
        { data: 'price_sale', searchable: false },
        { data: 'price', searchable: false },
        { data: 'created_at', searchable: false },
        { data: 'col-actions', searchable: false, orderable: false },
    ],
    "language": {
        "info": "_TOTAL_ registros",
        "search": "Buscar por:",
        "searchPlaceholder": "Nombre",
        "paginate": {
            "next": "Siguiente",
            "previous": "Anterior"
        },
        "lengthMenu": 'Mostrar <select>'+
                        '<option value="10">10</option>'+
                        '<option value="25">25</option>'+
                        '<option value="50">50</option>'+
                        '<option value="-1">Todos</option>'+
                        '</select> registros',
        "loadingRecords": "Cargando...",
        "processing": '<div class="progress" style="width: 40vw; margin-left: -10vw !important"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div></div> ',
        "emptyTable": "No se encontraron datos",
        "zeroRecords": "No se encontraron coincidencias",
        "infoEmpty": "",
        "infoFiltered": "",
        "paginate": {
          "previous": '<i class="fas fa-angle-left"></i>',
          "next": '<i class="fas fa-angle-right"></i>'
        }
    }
  });
  $('label').addClass('form-inline');
  $('select, input[type="search"]').addClass('form-control');
} */

function register() {
  const code = $('#code').val();
  const category = parseInt($('#category').val());
  const type = $('#type').val();
  const unit = parseInt($('#unit').val());
  const name = $('#name').val();
  const brand = $('#brand').val();
  const priceTotal = $('#priceTotal').val();
  const price = $('#price').val();
  const priceSale = $('#priceSale').val();

  if (!code || code.length === 0) {
    Swal.fire('Error!', 'Campo requerido', 'error');
    return;
  }

  if (category === 0) {
    Swal.fire('Error!', 'Debe seleccionar una categoría', 'error');
    return;
  }

  if (type === 0) {
    Swal.fire('Error!', 'Debe seleccionar un tipo de producto', 'error');
    return;
  }

  if (unit === 0) {
    Swal.fire('Error!', 'Debe seleccionar una unidad', 'error');
    return;
  }
  
  if (!name || name.length === 0) {
    Swal.fire('Error!', 'Campo requerido', 'error');
    return;
  }

  const data = {
    name,
    code,
    category,
    type,
    unit,
    brand,
    priceTotal,
    price,
    priceSale,
    _token: $('input[name=_token]').val(),
  };

  $.ajax({
    type: 'post',
    url: `../${ROUTE_MODULE}`,
    dataType: 'json',
    data,
    success: function(a){
      if (a.status) {
        location.replace(`${origin}/${ROUTE_MODULE}`);

      } else {
        Swal.fire('Error!', a.message, 'error');
      }
    },
    error: function(e) {
      Swal.fire('Error!', e.message, 'error');
    },
  });
}

function update() {
  const id = $('#idData').val();
  const code = $('#code').val();
  const category = parseInt($('#category').val());
  const type = $('#type').val();
  const unit = parseInt($('#unit').val());
  const name = $('#name').val();
  const brand = $('#brand').val();
  const priceTotal = $('#priceTotal').val();
  const price = $('#price').val();
  const priceSale = $('#priceSale').val();

  if (!code || code.length === 0) {
    Swal.fire('Error!', 'Campo requerido', 'error');
    return;
  }

  if (category === 0) {
    Swal.fire('Error!', 'Debe seleccionar una categoría', 'error');
    return;
  }

  if (type === 0) {
    Swal.fire('Error!', 'Debe seleccionar un tipo de producto', 'error');
    return;
  }

  if (unit === 0) {
    Swal.fire('Error!', 'Debe seleccionar una unidad', 'error');
    return;
  }
  
  if (!name || name.length === 0) {
    Swal.fire('Error!', 'Campo requerido', 'error');
    return;
  }

  const data = {
    id,
    name,
    code,
    category,
    type,
    unit,
    brand,
    priceTotal,
    price,
    priceSale,
    _token: $('input[name=_token]').val(),
  };

  $.ajax({
    type: 'put',
    url: `../../${ROUTE_MODULE}`,
    dataType: 'json',
    data,
    success: function(a){
      if (a.status) {
        location.replace(origin + `/${ROUTE_MODULE}`);

      }else {
        Swal.fire('Error!', a.message, 'error');
      }
    },
    error: function(e) {
      Swal.fire('Error!', e.message, 'error');
    }
  });
}

function modDelete(id) {
  $('#idDataDelete').val(id);
  $('#modalDelete').modal();
}

function remove() {
  const id = $('#idDataDelete').val();
  const data = {
    id,
    _token: $('input[name=_token]').val(),
  }
  $.ajax({
    type: 'delete',
    url: ``,
    dataType: 'json',
    data,
    success: function(a){
      if (a.status) {
        location.replace(origin+`/${ROUTE_MODULE}`);
      }
    },
    error: function(e) {
      Swal.fire('Error!', e.message, 'error');
    }
  })
}

function modStock(idMaterial) {
  $('#idMaterial').val(idMaterial);
  $('#modalStock').modal();

  const data = {
    idMaterial,
    _token: $('input[name=_token]').val(),
  }
  const STOCK_DATATABLE_PARAMS = {
    idTable: 'stockTable',
    url: `${ROUTE_MODULE}/stock`,
    type: 'get',
    data,
    columns: [
        { data: 'batch', name: 's.batch' },
        { data: 'amount', name: 's.amount', searchable: false, orderable: false },
        { data: 'expiration_date', name: 's.expiration_date', searchable: false },
    ],
    searching: true,
    searchPlaceholder: 'Lote',
    smallInputSearch: true,
  }
  initDatatable(STOCK_DATATABLE_PARAMS);
}

function modalAddStock() {
  
  $('#modalAddStock').modal();
}

function addStock() {
  const idMaterial = $('#idMaterial').val();
  const batch = $('#batch').val();
  const amount = $('#amount').val();
  const expirationDate = $('#expirationDate').val();

  if (amount < 0) {
    Swal.fire('Error!', 'La cantidad debe ser mayor a 0', 'error');
    return;
  }

  /* if (expirationDate < now()) {
    Swal.fire('Error!', 'La fecha de vencimiento debe ser mayor a la actual', 'error');
    return;
  } */

  const data = {
    idMaterial,
    batch,
    amount,
    expirationDate,
    _token: $('input[name=_token]').val(),
  };

  $.ajax({
    type: 'post',
    url: `../${ROUTE_MODULE}/add-stock`,
    dataType: 'json',
    data,
    success: function(a){
      if (a.status) {
        $('#modalAddStock').modal('hide');
        $('#stockTable').DataTable().ajax.reload();

      } else {
        Swal.fire('Error!', a.message, 'error');
      }
    },
    error: function(e) {
      Swal.fire('Error!', e.message, 'error');
    },
  });
}
