let origin = '';
let scrollX = false;
const ROUTE_MODULE = 'proveedores';
const MAIN_DATATABLE_PARAMS = {
    idTable: 'mainTable',
    url: `${ROUTE_MODULE}/data`,
    type: 'get',
    columns: [
        { data: 'document_number', },
        { data: 'business_name' },
        { data: 'phone', searchable: false },
        { data: 'address', searchable: false },
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
    const businessName = $('#businessName').val();
    const phone = $('#phone').val();
    const address = $('#address').val();

    if (!documentNumber || documentNumber.length < 8) {
        showAlert('Error!', 'Campo requerido', 'error', 'Ok', 'documentNumber');
        return;
    }

    if (!businessName || businessName.length === 0) {
        showAlert('Error!', 'Campo requerido', 'error', 'Ok', 'businessName');
        return;
    }

    const data = {
        documentNumber,
        businessName,
        phone,
        address,
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
    const businessName = $('#businessName').val();
    const phone = $('#phone').val();
    const address = $('#address').val();

    if (!documentNumber || documentNumber.length < 8) {
        showAlert('Error!', 'Campo requerido', 'error', 'Ok', 'documentNumber');
        return;
    }

    if (!businessName || businessName.length === 0) {
        showAlert('Error!', 'Campo requerido', 'error', 'Ok', 'businessName');
        return;
    }
    
    const data = {
        id,
        documentNumber,
        businessName,
        phone,
        address,
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
