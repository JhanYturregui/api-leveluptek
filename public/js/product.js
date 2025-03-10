let origin = '';
let scrollX = false;
let pricesList = [];
const path = window.location.pathname.split('/');
const IS_UPDATE = path[path.length - 1] > 0;
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

function getPrices() {
  if (IS_UPDATE) {
    let prices = JSON.parse($('#prices').val());
    pricesList = prices.map(price => {
      return parseFloat(price.price)
    })
    showPrices();
  } 
}

window.onload = function() {
  origin = window.location.origin;
  scrollX = window.screen.width <= 1240 ? true : false;
  initDatatable(MAIN_DATATABLE_PARAMS);
  getPrices();
}

window.onresize = function () {
  scrollX = window.screen.width <= 1240 ? true : false;
  initDatatable(MAIN_DATATABLE_PARAMS);
}

function register() {
  const code = $('#code').val();
  const name = $('#name').val();
  const category = $('#category').val();
  const favorite = $('#favorite').is(':checked') ? 1 : 0;

  if (!code || code.length === 0) {
      showAlert('Error!', 'Campo requerido', 'error', 'Ok', 'code');
      return;
  }

  if (!name || name.length === 0) {
      showAlert('Error!', 'Campo requerido', 'error', 'Ok', 'name');
      return;
  }

  if (pricesList.length === 0) {
      showAlert('Error!', 'Debe agregar al menos un precio', 'error', 'Ok', 'price');
      return;
  }

  const data = {
      name,
      code,
      category,
      favorite,
      pricesList,
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
    const name = $('#name').val();
    const category = $('#category').val();
    const favorite = $('#favorite').is(':checked') ? 1 : 0;

    if (!code || code.length === 0) {
        showAlert('Error!', 'Campo requerido', 'error', 'Ok', 'code');
        return;
    }

    if (!name || name.length === 0) {
        showAlert('Error!', 'Campo requerido', 'error', 'Ok', 'name');
        return;
    }

    if (pricesList.length === 0) {
        showAlert('Error!', 'Debe agregar al menos un precio', 'error', 'Ok', 'price');
        return;
    }

    const data = {
        id,
        name,
        code,
        category,
        favorite,
        pricesList,
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

$('#price').on('keyup', function(e) {
  if (e.keyCode === 13) {
    addPrice();
  }
})

function addPrice() {
  const price = parseFloat($('#price').val());
  const exists = pricesList.find(pr => pr === price);
  if ( !exists ) {
    pricesList.push(price);
    $('#price').val('');
    $('#price').focus();
  }
  showPrices();
}

function removePrice(price) {
  pricesList = pricesList.filter(pr => pr !== price);
  showPrices();
}

function showPrices() {
  $('#divPricesList').html('');

  let row = `
    <table class="table table-bordered mt-3 rounded">
      <thead class="bg-primary text-white">
        <tr>
          <th>Precio</th>
          <th>Quitar</th>
        </tr>
      </thead>
      <tbody>`

  pricesList.forEach(price => {
    row += `
        <tr>
          <td style="width:70%; word-wrap: break-word; white-space: normal;">${price}</td>
          <td class="text-center"><i class="fas fa-trash" style="color: #e57373; cursor: pointer" title="Quitar" onclick="removePrice(${price})"></i></td>
        </tr>`;
  })
  row += `
      </tbody>
    </table>`;
  $('#divPricesList').html(row);
}
