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
