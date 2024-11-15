let origin = '';
let scrollX = false;
const ROUTE_MODULE = 'coworkings';

window.onload = function() {
  origin = window.location.origin;
  scrollX = window.screen.width <= 1240 ? true : false;
  initDatatable('mainTable');
}

window.onresize = function () {
  scrollX = window.screen.width <= 1240 ? true : false;
  initDatatable('mainTable');
}

function initDatatable (idTable) {
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
        { data: 'id', searchable: false },
        { data: 'name' },
        { data: 'description', searchable: false, orderable: false },
        { data: 'created_at', searchable: false },
        { data: 'updated_at', searchable: false },
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
}

function register() {
  const name = $('#name').val();
  const description = $('#description').val();

  if (!name || name.length === 0) {
    Swal.fire('Error!', 'Campo requerido', 'error');
  }

  const data = {
    name,
    description,
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
  const name = $('#name').val();
  const description = $('#description').val();

  if (!name || name.length === 0) {
    Swal.fire('Error!', 'Campo requerido', 'error');
  }
  
  const data = {
    id,
    name,
    description,
    _token: $('input[name=_token]').val()
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
    url: `../${ROUTE_MODULE}`,
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
