let origin = '';
let scrollX = false;
let materialsList = [];
const path = window.location.pathname.split('/');
const IS_UPDATE = path[path.length - 1] > 0;
const ROUTE_MODULE = 'tratamientos';
const MAIN_DATATABLE_PARAMS = {
  idTable: 'mainTable',
  url: `${ROUTE_MODULE}/data`,
  type: 'get',
  columns: [
    { data: 'id', searchable: false },
    { data: 'name' },
    { data: 'price' },
    { data: 'materials', name: 'materials' },
    { data: 'created_at_formatted', searchable: false },
    { data: 'updated_at_formatted', searchable: false },
    { data: 'col-actions', searchable: false, orderable: false },
],
  searching: true,
}

function getMaterials() {
  if (IS_UPDATE) {
    let materials = JSON.parse($('#materials').val());
    materialsList = materials.map(material => {
      return { id: material.id, code: material.code, name: material.name, amount: material.pivot.amount}
    })
    showMaterialsList();
  } 
}

window.onload = function() {
  origin = window.location.origin;
  scrollX = window.screen.width <= 1240 ? true : false;
  initDatatable(MAIN_DATATABLE_PARAMS);
  getMaterials();
}

window.onresize = function () {
  scrollX = window.screen.width <= 1240 ? true : false;
  initDatatable(MAIN_DATATABLE_PARAMS);
}

function register() {
  const name = $('#name').val();

  if (!name || name.length === 0) {
    Swal.fire('Error!', 'Campo requerido', 'error');
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

function listMaterials() {
  const url = IS_UPDATE ? `../../materiales/data` : `../materiales/data`;
  const MATERIALS_DATATABLE_PARAMS = {
    idTable: 'materialsTable',
    url,
    type: 'get',
    columns: [
        { data: 'code', searchable: false },
        { data: 'name', name: 'm.name' },
        {
          data: null, 
          render: function(data, type, row) {
              return `<button class="btn btn-sm btn-primary" onclick="addMaterial(${row.id}, '${row.code}', '${row.name}')"><i class="fas fa-plus"></i></button>`;
          }
      }
    ],
    searching: true,
    smallInputSearch: true,
  }
  initDatatable(MATERIALS_DATATABLE_PARAMS);
  $('#modalMaterials').modal();
}

function addMaterial(id, code, name) {
  const newMaterial = {id, code, name};
  const material = materialsList.find(element => newMaterial.id === element.id);
  if (material) {
    material.amount++;
  } else {
    newMaterial.amount = 1;
    materialsList.push(newMaterial);
  }
  showMaterialsList();
}

function updateMaterial(id, amount) {
  materialsList.map(material => material.id === id ? material.amount = parseFloat(amount) : '');
}

function removeMaterial(id) {
  materialsList = materialsList.filter(material => material.id !== id);
  showMaterialsList();
}

$('#materialId').on('keyup', function(e) {
  if (e.keyCode === 13) {
    findMaterial();
  }
})

function findMaterial() {
  const materialId = $('#materialId').val();
  alert(materialId)
}

function showMaterialsList() {
  $('#divMaterialsList').html('');

  let materialInputGroup = `
    <table class="table table-bordered mt-3 rounded">
      <thead class="bg-primary text-white">
        <tr>
          <th>Material</th>
          <th>Cantidad</th>
          <th>Quitar</th>
        </tr>
      </thead>
      <tbody>`

  materialsList.forEach(material => {
    materialInputGroup += `
        <tr>
          <td style="width:70%">${material.name}</td>
          <td><input type="number" class="form-control form-control-sm" value="${material.amount}" onblur="updateMaterial(${material.id}, this.value)" /></td>
          <td class="text-center"><i class="fas fa-trash" style="color: #e57373; cursor: pointer" title="Quitar" onclick="removeMaterial(${material.id})"></i></td>
        </tr>`;
  })
  materialInputGroup += `
      </tbody>
    </table>`;
  $('#divMaterialsList').html(materialInputGroup);
}

function register() {
  const name = $('#name').val();
  const price = $('#price').val();

  if (!name || name.length === 0) {
    Swal.fire('Error!', 'Campo requerido', 'error');
    return;
  }

  if (!price || price.length === 0) {
    Swal.fire('Error!', 'Campo requerido', 'error');
    return;
  }

  if (price <= 0) {
    Swal.fire('Error!', 'El precio debe ser mayor a 0', 'error');
    return;
  }

  const data = {
    name,
    price,
    materialsList,
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
  const price = $('#price').val();

  if (!name || name.length === 0) {
    Swal.fire('Error!', 'Campo requerido', 'error');
    return;
  }

  if (!price || price.length === 0) {
    Swal.fire('Error!', 'Campo requerido', 'error');
    return;
  }

  if (price <= 0) {
    Swal.fire('Error!', 'El precio debe ser mayor a 0', 'error');
    return;
  }
  
  const data = {
    id,
    name,
    price,
    materialsList,
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
