let origin = '';
let scrollX = false;
const CASH_SESSION_ID = parseInt($('#cashSessionId').val());
const ROUTE_MODULE_CASH_SESSION = 'caja';
const ROUTE_MODULE = 'ventas';
const MAIN_DATATABLE_PARAMS = {
  idTable: 'mainTable',
  url: `${ROUTE_MODULE}/data`,
  type: 'get',
  columns: [
    { data: 'id', searchable: false },
    { data: 'total_amount' },
    { data: 'total_amount' },
    { data: 'created_at_formatted', searchable: false },
    { data: 'col-actions', searchable: false, orderable: false },
],
  searching: true,
}

let productsList = [];
let totalAmount = 0;
const INITIAL_QUANTITY = 1;

window.onload = function() {
  if (CASH_SESSION_ID === 0) {
    $('#modalRegisterCashSession').modal();
    $('#openingAmount').focus();
    return;
  }
  origin = window.location.origin;
  scrollX = window.screen.width <= 1240 ? true : false;
  initDatatable(MAIN_DATATABLE_PARAMS);
}

window.onresize = function () {
  scrollX = window.screen.width <= 1240 ? true : false;
  initDatatable(MAIN_DATATABLE_PARAMS);
}

function registerCashSession() {
  const date = $('#date').val();
  const openingAmount = $('#openingAmount').val();
  const comment = $('#comment').val();

  if (!openingAmount === '') {
    showAlert('Error!', 'Campo requerido', 'error', 'Ok', 'openningAmount');
    return;
  }

  const data = {
    date,
    openingAmount,
    comment,
    _token: $('input[name=_token]').val(),
  };

  $.ajax({
    type: 'post',
    url: `../${ROUTE_MODULE_CASH_SESSION}`,
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

$('#productCode').on('keyup', function(e) {
  if (e.keyCode === 13) {
    getProductByCode($('#productCode').val());
    $('#productCode').val('');
  }
})

function findProduct(productId) {
    return productsList.find((product) => product.id === productId);
}

function addProduct(productToAdd) {
    console.log('productToAdd', productToAdd)
    const { id } = productToAdd
    const product = findProduct(id);
    if (product) {
        product.quantity++;
    } else {
      productsList.unshift(productToAdd);
    }
    fillShoppingCart();
    calculateTotalAmount();
}

function updateProduct(productId) {
    const product = findProduct(productId);
    product.quantity = $(`#quantity-${productId}`).val();
    product.price = $(`#price-${productId}`).val();
}

function removeProduct(productId) {
    productsList = productsList.filter(product =>  product.id !== productId);
    fillShoppingCart();
    calculateTotalAmount();
}

function calculateTotalAmount() {
    totalAmount = 0;
    productsList.forEach(product => {
        const quantity = parseFloat($(`#quantity-${product.id}`).val());
        const price = parseFloat($(`#price-${product.id}`).val());
        const partial = quantity * price;
        totalAmount += partial;
    });
    $(`#textTotalAmount`).text(`S/. ${totalAmount.toFixed(2)}`);
}

function fillShoppingCart() {
    $('#bodyShoppingCart').html('');
    let bodyContent = '';
    productsList.forEach(product => {
        const prices = product.prices
        bodyContent +=  `<tr>
                            <td class="wide-column">${product.name}</td>
                            <td><input type="number" id="quantity-${product.id}" class="small-input" value="${product.quantity}" onblur="updateProduct(${product.id}); calculateTotalAmount()" /></td>
                            <td>
                                <select class="small-input" id="price-${product.id}" onchange="updateProduct(${product.id}); calculateTotalAmount()">`;
                for (let i = 0; i < prices.length; i++) {
                    const selected = parseFloat(prices[i]) === parseFloat(product.price) ? 'selected' : '';
                    bodyContent += `<option value="${prices[i]}" ${selected}>${prices[i]}</option>`;
                }
        bodyContent +=         `</select>
                            </td>"
                            <td class="text-center"><i class="fas fa-trash" style="cursor: pointer" onclick="removeProduct(${product.id})"></i></td>
                        </tr>`;
    })
    $('#bodyShoppingCart').html(bodyContent);
}

function getProductByCode(code) {
  const data = {
    code,
    _token: $('input[name=_token]').val(),
  };

  $.ajax({
    type: 'get',
    url: `../../productos/obtener`,
    dataType: 'json',
    data,
    success: function(a){
      if (a.status) {
        const product = a.data;
        if (product) {
            const prices = product.prices.map(({price}) => ({price}));
            addProduct({ id: product.id, name: product.name, quantity: INITIAL_QUANTITY, prices: prices, price: prices[0].price });
            $('#codeProduct').focus();
        }

      } else {
        Swal.fire('Error!', a.message, 'error');
      }
    },
    error: function(e) {
      Swal.fire('Error!', e.message, 'error');
    },
  });
}

$('#formRegister').on('submit', function (event) {
    event.preventDefault();

    if (productsList.length === 0) {
        showAlert('Error!', 'Debe agregar al menos un producto', 'error', 'Ok', 'codeProduct');
        return;
    }
    
    const products = productsList.map(({id, quantity, price}) => ({id, quantity, price}));
    const data = new FormData(this);
    data.append('supplierId', 1);
    data.append('productsList', JSON.stringify(products));
    data.append('totalAmount', totalAmount);

    $.ajax({
        method: 'post',
        url: `../${ROUTE_MODULE}`,
        dataType: 'json',
        data,
        contentType: false,
        cache: false,
        processData: false,
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

})

/************************************************ CUSTOMERS ************************************************/
function selectCustomer({id, documentNumber, fullName}) {
    addCustomer({id, documentNumber, fullName})
    $('#modalCustomers').modal('hide');
}

function addCustomer({id, documentNumber, fullName}) {
    $('#idCustomer').val(id);
    $('#documentNumberCustomer').val('');
    $('#fullNameCustomer').val(`${documentNumber} - ${fullName}`);
    $('#btnRemoveCustomer').css('display', 'inline');
    $('#productCode').focus();
}

$('#btnRemoveCustomer').on('click', function() {
    $('#idCustomer').val(null);
    $('#documentNumberCustomer').val('');
    $('#fullNameCustomer').val('');
    $('#btnRemoveCustomer').css('display', 'none');
})

$('#documentNumberCustomer').on('keyup', function(e) {
    if (e.keyCode === 13) {
        getCustomerByDocumentNumber($('#documentNumberCustomer').val());
        $('#documentNumberCustomer').val('');
    }
})

function getCustomerByDocumentNumber(documentNumber) {
    const data = {
        documentNumber,
        _token: $('input[name=_token]').val(),
    };
  
    $.ajax({
        type: 'get',
        url: `../../clientes/obtener`,
        dataType: 'json',
        data,
        success: function(a){
            if (a.status) {
                const customer = a.data;
                if (customer) {
                    addCustomer({id: customer.id, documentNumber: customer.document_number, fullName: customer.full_name });
                }
    
            } else {
                Swal.fire('Error!', a.message, 'error');
            }
        },
        error: function(e) {
            Swal.fire('Error!', e.message, 'error');
        },
    });
}
