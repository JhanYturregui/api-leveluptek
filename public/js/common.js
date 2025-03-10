function initDatatable (params) {
  destroyDataTable(params.idTable);

  const classInputSearch = params.smallInputSearch ? 'form-control form-control-sm mb-2' : 'form-control mb-2';

  $('#' + params.idTable).DataTable({
    "serverSide": true,
    "processing": true,
    "responsive": true,
    "searching": params.searching,
    "scrollX": scrollX,
    "autoWidth": false,
    "ajax": {
      "url": params.url,
      "type": params.type,
      "data": params.data ?? {},
    },
    "columns": params.columns,
    "language": {
        "info": "_TOTAL_ registros",
        "search": "Buscar por:",
        "searchPlaceholder": params.searchPlaceholder ?? "Nombre",
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
  $('select, input[type="search"]').addClass(classInputSearch);
}

function destroyDataTable(idTable) {
  const table = $('#' + idTable).DataTable();
  table.destroy();
}

function showAlert(title, text, icon, confirmButtonText, id) {
  Swal.fire({
    title,
    text,
    icon,
    confirmButtonText,
    didClose: () => {
      setTimeout(() => {
        $(`#${id}`).focus();
      }, 10);
    }
  })
}


function getProductsForTransactions() {
    const isSale = window.location.pathname.includes('ventas') ? true : false;
    const PRODUCTS_DATATABLE_PARAMS = {
        idTable: 'productsTable',
        url: `../../productos/transacciones`,
        type: 'get',
        data: {
          isSale,
        },
        columns: [
            { data: 'code', name: 'p.code', searchable: false },
            { data: 'name', name: 'p.name' },
            { data: 'col-select', searchable: false, orderable: false },
        ],
        searching: true,
    }
    initDatatable(PRODUCTS_DATATABLE_PARAMS);
    $('#modalProducts').modal();
}

function getSuppliersForTransactions() {
  const SUPPLIERS_DATATABLE_PARAMS = {
    idTable: 'suppliersTable',
    url: `../../proveedores/transacciones`,
    type: 'get',
    columns: [
      { data: 'document_number' },
      { data: 'business_name' },
      { data: 'col-select', searchable: false, orderable: false },
  ],
    searching: true,
  }
  initDatatable(SUPPLIERS_DATATABLE_PARAMS);
  $('#modalSuppliers').modal();
}

function getCustomersForTransactions() {
  const CUSTOMERS_DATATABLE_PARAMS = {
    idTable: 'customersTable',
    url: `../../clientes/transacciones`,
    type: 'get',
    columns: [
      { data: 'document_number' },
      { data: 'full_name' },
      { data: 'col-select', searchable: false, orderable: false },
  ],
    searching: true,
  }
  initDatatable(CUSTOMERS_DATATABLE_PARAMS);
  $('#modalCustomers').modal();
}

let currentCategory = 0;
function getProductsByCategory(categoryId) {
    $.ajax({
        type: 'get',
        url: `../../productos/obtener_por_categoria/${categoryId}`,
        dataType: 'json',
        //data,
        success: function(a){
            if (a.status) {
                $('#containerProductsByCategory').html('');
                const products = a.data;
                let productsHtml = '';
                products.forEach(product => {
                    if (product.stock > 0 || window.location.pathname.includes('compras')) {
                        const prices = product.prices.map(price => price.price);
                        productsHtml += `<span class="badge badge-pill badge-info p-2 mr-1" onclick="addProduct({ id: ${product.id}, name: '${product.name}', quantity: 1, prices: [${prices}], price: ${prices[0]}, stock: ${product.stock} })">${product.name}</span>`;
                    }
                });
                $('#containerProductsByCategory').html(productsHtml);
                $(`#spanCategory-${currentCategory}`).removeClass('badge-primary');
                $(`#spanCategory-${currentCategory}`).addClass('badge-dark');
                currentCategory = categoryId;
                $(`#spanCategory-${categoryId}`).removeClass('badge-dark');
                $(`#spanCategory-${categoryId}`).addClass('badge-primary');

            } else {
                Swal.fire('Error!', a.message, 'error');
            }
        },
        error: function(e) {
            Swal.fire('Error!', e.message, 'error');
        },
    });
}