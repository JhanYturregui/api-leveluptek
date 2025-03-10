let origin = '';
let scrollX = false;
const CASH_SESSION_ID = parseInt($('#cashSessionId').val());
const ROUTE_MODULE_CASH_SESSION = 'caja';
const ROUTE_MODULE = 'movimientos';
const MAIN_DATATABLE_PARAMS = {
  idTable: 'mainTable',
  url: `${ROUTE_MODULE}/data`,
  type: 'get',
  columns: [
    { data: 'type', searchable: false },
    { data: 'description' },
    { data: 'amount' },
    { data: 'created_at_formatted', searchable: false },
    { data: 'updated_at_formatted', searchable: false },
    { data: 'col-actions', searchable: false, orderable: false },
],
  searching: true,
}

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

function register() {
    const type = $('#type').val();
    const amount = $('#amount').val();
    const description = $('#description').val();
    const customerId = $('#customerId').val();

    if (amount <= 0) {
        showAlert('Error!', 'El monto debe ser un valor mayor a 0', 'error', 'Ok', 'amount');
        return;
    }

    if (type !== 'pago' && (!description || description.length === 0)) {
        showAlert('Error!', 'Campo requerido', 'error', 'Ok', 'description');
        return;
    }
    
    if (type === 'pago' && parseInt(customerId) === 0) {
        showAlert('Error!', 'Seleccione cliente', 'error', 'Ok', 'customerId');
        return;
    }

    const data = {
        type,
        amount,
        description,
        customerId,
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
    const type = $('#type').val();
    const amount = $('#amount').val();
    const description = $('#description').val();
    const customerId = $('#customerId').val();

    if (amount <= 0) {
        showAlert('Error!', 'El monto debe ser un valor mayor a 0', 'error', 'Ok', 'amount');
        return;
    }

    if (type !== 'pago' && (!description || description.length === 0)) {
        showAlert('Error!', 'Campo requerido', 'error', 'Ok', 'description');
        return;
    }
    
    if (type === 'pago' && parseInt(customerId) === 0) {
        showAlert('Error!', 'Seleccione cliente', 'error', 'Ok', 'customerId');
        return;
    }
  
    const data = {
        id,
        type,
        amount,
        description,
        customerId,
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
            } else {
                showAlert('Error!', a.message, 'error', 'Ok', 'amount');
                $('#modalDelete').modal('hide');
            }
        },
        error: function(e) {
            Swal.fire('Error!', e.message, 'error');
        }
    })
}

function selectCustomer({id, documentNumber, fullName}) {
    $('#customerId').val(id);
    $('#customer').val(`${documentNumber} - ${fullName}`);
    $('#btnSearchCustomer').css('display', 'none');
    $('#btnRemoveCustomer').css('display', 'inline');
    $('#modalCustomers').modal('hide');
}

function removeCustomer() {
    $('#customerId').val(0);
    $('#customer').val(``);
    $('#btnSearchCustomer').css('display', 'inline');
    $('#btnRemoveCustomer').css('display', 'none');
}
