const ROUTE_MODULE = '';
const ROUTE_MODULE_CASH_SESSION = 'caja';

function modalCloseCashSession(id) {
    $('#modalCloseCashSession').modal();
}
  
function closeCashSession() {
    const totalInRegiser = $('#totalInRegiser').val();
    const data = {
        totalInRegiser,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'post',
        url: `../${ROUTE_MODULE_CASH_SESSION}/close`,
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