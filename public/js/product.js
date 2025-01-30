let origin = '';
let scrollX = false;
const ROUTE_MODULE = 'productos';
const MAIN_DATATABLE_PARAMS = {
  idTable: 'mainTable',
  url: `${ROUTE_MODULE}/data`,
  type: 'get',
  columns: [
    { data: 'code', name: 'p.code' },
    { data: 'name', name: 'p.name' },
    { data: 'category_name', name: 'c.name', searchable: false, orderable: false },
    { data: 'code', searchable: false },
    { data: 'prices', searchable: false, orderable: false },
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

function register() {
  const code = $('#code').val();
  const name = $('#name').val();

  if (!name || name.length === 0) {
    Swal.fire('Error!', 'Campo requerido', 'error');
    return;
  }

  const data = {
    name,
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

  if (!name || name.length === 0) {
    Swal.fire('Error!', 'Campo requerido', 'error');
    return;
  }
  
  const data = {
    id,
    name,
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
