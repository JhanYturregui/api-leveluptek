let origin = '';
let scrollX = false;
const ROUTE_MODULE = 'clientes';
const MAIN_DATATABLE_PARAMS = {
    idTable: 'mainTable',
    url: `${ROUTE_MODULE}/data`,
    type: 'get',
    columns: [
        { data: 'document_number', },
        { data: 'full_name' },
        { data: 'credit_limit', searchable: false },
        { data: 'available_balance', searchable: false },
        { data: 'created_at_formatted', searchable: false },
        { data: 'updated_at_formatted', searchable: false },
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
    const documentNumber = $('#documentNumber').val();
    const fullName = $('#fullName').val();
    const creditLimit = $('#creditLimit').val();

    if (!documentNumber || documentNumber.length < 8) {
        showAlert('Error!', 'Campo requerido', 'error', 'Ok', 'documentNumber');
        return;
    }

    if (!fullName || fullName.length === 0) {
        showAlert('Error!', 'Campo requerido', 'error', 'Ok', 'fullName');
        return;
    }

    if (creditLimit < 0) {
        showAlert('Error!', 'El límite de crédito debe ser mayor o igual a 0', 'error', 'Ok', 'creditLimit');
        return;
    }

    const data = {
        documentNumber,
        fullName,
        creditLimit,
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
    const documentNumber = $('#documentNumber').val();
    const fullName = $('#fullName').val();
    const creditLimit = $('#creditLimit').val();

    if (!documentNumber || documentNumber.length < 8) {
        showAlert('Error!', 'Campo requerido', 'error', 'Ok', 'documentNumber');
        return;
    }

    if (!fullName || fullName.length === 0) {
        showAlert('Error!', 'Campo requerido', 'error', 'Ok', 'fullName');
        return;
    }

    if (creditLimit < 0) {
        showAlert('Error!', 'El límite de crédito debe ser mayor o igual a 0', 'error', 'Ok', 'creditLimit');
        return;
    }
    
    const data = {
        id,
        documentNumber,
        fullName,
        creditLimit,
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
