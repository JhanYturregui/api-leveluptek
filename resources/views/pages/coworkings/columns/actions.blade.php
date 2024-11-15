<div class="dropdown">
  <a
    class="btn btn-sm btn-icon-only text-light"
    href="#"
    role="button"
    data-toggle="dropdown"
    aria-haspopup="true"
    aria-expanded="false"
  >
    <i class="fas fa-ellipsis-v"></i>
  </a>
  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
    <a class="dropdown-item" href="{{ route('coworkings_edit', $id) }}">
      <i class="fas fa-pen"></i>
      {{ __('Editar') }}
    </a>
    <a class="dropdown-item" href="javascript:modDelete({{ $id }})">
      <i class="fas fa-trash"></i>
      {{ __('Eliminar') }}
    </a>
  </div>
</div>
