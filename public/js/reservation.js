let origin = '';
let scrollX = false;
const ROUTE_MODULE = 'reservations';
const RESERVATION_DURATION = 60;
const USER_ROLE = $('#userRole').val();
const USER_ROLE_ADMIN = $('#adminRole').val();

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

  let columns = [];
  if (USER_ROLE === USER_ROLE_ADMIN) {
    columns = [
        { data: 'id', searchable: false },
        { data: 'user_name' },
        { data: 'coworking_name' },
        { data: 'start_date' },
        { data: 'end_date' },
        { data: 'col-state', searchable: false, orderable: false },
        { data: 'col-action-change-state', searchable: false, orderable: false },
    ];
  } else {
    columns = [
      { data: 'id', searchable: false },
      { data: 'coworking_name' },
      { data: 'start_date' },
      { data: 'end_date' },
      { data: 'col-state', searchable: false, orderable: false },
  ];
  }

  const idCoworking = $('#coworking').val();
  $('#' + idTable).DataTable({
    "serverSide": true,
    "processing": true,
    "responsive": true,
    "searching": false,
    "scrollX": scrollX,
    "ajax": {
      "url": `${ROUTE_MODULE}/data`,
      "type": "get",
      "data": {
        "idCoworking": idCoworking,
      }
    },
    "columns": columns,
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

function formattedDate(date) {
  date = new Date(date);
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');
  const formattedDate = `${year}-${month}-${day}T${hours}:${minutes}`;
  return formattedDate;
}

function calculateEndDate(startDate) {
  startDate = new Date(startDate);
  let endDate = startDate.setMinutes(startDate.getMinutes() + RESERVATION_DURATION);
  endDate = formattedDate(endDate);
  $('#endDate').val(endDate);
}

function register() {
  const idCoworking = $('#coworking').val();
  const startDate = $('#startDate').val();
  const endDate = $('#endDate').val();

  if (!startDate || !endDate) {
    Swal.fire('Error!', 'Las fechas son requeridas', 'error');
  }

  if (startDate <= endDate) { 
    Swal.fire('Error!', 'La fecha de inicio no puede ser menor a la fecha final', 'error');
  }

  const data = {
    idCoworking,
    startDate,
    endDate,
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

function modChangeState(id, state) {
  $('#idDataChangeState').val(id);
  $('#newState').val(state);
  $('#modalChangeState').modal();
}

function changeState() {
  const id = $('#idDataChangeState').val();
  const state = $('#newState').val();
  
  const data = {
    id,
    state,
    _token: $('input[name=_token]').val()
  };

  $.ajax({
    type: 'put',
    url: `${ROUTE_MODULE}/change-state`,
    dataType: 'json',
    data,
    success: function(a){
      if (a.status) {
        Swal.fire('Ok!', a.message, 'success');
        initDatatable('mainTable');
        $('#modalChangeState').modal('hide');

      }else {
        Swal.fire('Error!', a.message, 'error');
      }
    },
    error: function(e) {
      Swal.fire('Error!', e.message, 'error');
    }
  });
}

