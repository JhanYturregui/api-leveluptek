let origin = '';
let scrollX = false;
const path = window.location.pathname.split('/');
const IS_UPDATE = path[path.length - 1] > 0;
const CASH_SESSION_ID = parseInt($('#cashSessionId').val());
const ROUTE_MODULE_CASH_SESSION = 'caja';
const ROUTE_MODULE = 'compras';
const MAIN_DATATABLE_PARAMS = {
  idTable: 'mainTable',
  url: `${ROUTE_MODULE}/data`,
  type: 'get',
  columns: [
    { data: 'id', searchable: false },
    { data: 'business_name', name: 's.business_name', orderable: false },
    { data: 'total_amount', searchable: false },
    { data: 'created_at_formatted', searchable: false },
    { data: 'col-actions', searchable: false, orderable: false },
],
  searching: true,
}

let productsList = [];
let totalAmount = 0;
const INITIAL_QUANTITY = 1;
const INITIAL_PRICE = 1;
const DECIMALS_NUMBER = 2;

function getProducts() {
  if (IS_UPDATE) {
    let products = JSON.parse($('#products').val());
    productsList = products.map(product => {
      return { id: product.id, name: product.name, quantity: product.pivot.quantity, price: product.pivot.price}
    })
    fillShoppingCart();
    calculateTotalAmount();
  } 
}

function getPurchaseImage() {
    if (IS_UPDATE) {
      let urlPurchaseImage = $('#urlPurchaseImage').val();
      console.log(urlPurchaseImage);
      if (urlPurchaseImage) {
        imagePreview.src = urlPurchaseImage;
        imagePreviewContainer.style.display = 'block';
      } else {
        imagePreview.src = '';
        imagePreviewContainer.style.display = 'none';
      }
    } 
}

window.onload = function() {
  if (CASH_SESSION_ID === 0) {
    $('#modalRegisterCashSession').modal();
    $('#openingAmount').focus();
    return;
  }
  $('#productCode').focus();
  origin = window.location.origin;
  scrollX = window.screen.width <= 1240 ? true : false;
  initDatatable(MAIN_DATATABLE_PARAMS);
  getProducts();
  getPurchaseImage();
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

/************************************************ IMAGE ************************************************/
let imagePreview = document.getElementById('invoiceImagePreview');
let imagePreviewContainer = document.getElementById('invoiceImagePreviewContainer');
$('#invoice').on('change', function () {
  const file = this.files[0];

  if (file) {
    if (!file.type.startsWith('image/')) {
      showAlert('Error', 'Por favor selecciona un archivo de imagen.', 'error', 'Ok');
      return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
      imagePreview.src = e.target.result;
      imagePreviewContainer.style.display = 'block';
    };

    reader.readAsDataURL(file);
  } else {
    imagePreview.src = '';
    imagePreviewContainer.style.display = 'none';
  }
});

$('#removeInvoiceImagePreview').on('click', function(event) {
  event.preventDefault();
  $('#invoice').val('');
  imagePreview.src = '';
  imagePreviewContainer.style.display = 'none';
})

/************************************************ SUPPLIERS ************************************************/
function selectSupplier({id, documentNumber, businessName}) {
    addSupplier({id, documentNumber, businessName})
    $('#modalSuppliers').modal('hide');
}

function addSupplier({id, documentNumber, businessName}) {
    $('#idSupplier').val(id);
    $('#documentNumberSupplier').val('');
    $('#businessNameSupplier').val(`${documentNumber} - ${businessName}`);
    $('#btnRemoveSupplier').css('display', 'inline');
    $('#productCode').focus();
}

$('#btnRemoveSupplier').on('click', function() {
    $('#idSupplier').val(null);
    $('#documentNumberSupplier').val('');
    $('#businessNameSupplier').val('');
    $('#btnRemoveSupplier').css('display', 'none');
})

$('#documentNumberSupplier').on('keyup', function(e) {
    if (e.keyCode === 13) {
        getSupplierByDocumentNumber($('#documentNumberSupplier').val());
        $('#documentNumberSupplier').val('');
    }
})

function getSupplierByDocumentNumber(documentNumber) {
    const data = {
        documentNumber,
      _token: $('input[name=_token]').val(),
    };
  
    $.ajax({
      type: 'get',
      url: `../../proveedores/obtener`,
      dataType: 'json',
      data,
      success: function(a){
        if (a.status) {
            const supplier = a.data;
            if (supplier) {
                addSupplier({id: supplier.id, documentNumber: supplier.document_number, businessName: supplier.business_name });
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

/************************************************ PRODUCTS ************************************************/
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
    const { id } = productToAdd
    const product = findProduct(id);
    if (product) {
        product.quantity++;
    } else {
      productToAdd.price = INITIAL_PRICE;
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
        $(`#price-${product.id}`).val(price.toFixed(DECIMALS_NUMBER));
        $(`#quantity-${product.id}`).val(quantity.toFixed(DECIMALS_NUMBER));
    });
    $(`#textTotalAmount`).text(`S/. ${totalAmount.toFixed(DECIMALS_NUMBER)}`);
}

function fillShoppingCart() {
    $('#bodyShoppingCart').html('');
    let bodyContent = '';
    productsList.forEach(product => {
        bodyContent +=  `<tr>
                            <td class="wide-column">${product.name}</td>
                            <td><input type="number" id="quantity-${product.id}" class="small-input" value="${product.quantity}" onblur="updateProduct(${product.id}); calculateTotalAmount()" /></td>
                            <td><input type="number" id="price-${product.id}" class="small-input" value="${product.price}" onblur="updateProduct(${product.id}); calculateTotalAmount()" /></td>
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
            addProduct({ id: product.id, name: product.name, quantity: INITIAL_QUANTITY, prices: prices });
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

/************************************************ PURCHASES ************************************************/
$('#formRegister').on('submit', function (event) {
    event.preventDefault();

    if (productsList.length === 0) {
        showAlert('Error!', 'Debe agregar al menos un producto', 'error', 'Ok', 'codeProduct');
        return;
    }
    
    const products = productsList.map(({id, quantity, price}) => ({id, quantity, price}));
    const data = new FormData(this);
    data.append('supplierId', $('#idSupplier').val());
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

$('#formUpdate').on('submit', function (event) {
    event.preventDefault();

    if (productsList.length === 0) {
        showAlert('Error!', 'Debe agregar al menos un producto', 'error', 'Ok', 'codeProduct');
        return;
    }
    
    const products = productsList.map(({id, quantity, price}) => ({id, quantity, price}));
    const data = new FormData(this);
    data.append('supplierId', $('#idSupplier').val());
    data.append('productsList', JSON.stringify(products));
    data.append('totalAmount', totalAmount);

    $.ajax({
        method: 'post',
        url: `../../${ROUTE_MODULE}`,
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
