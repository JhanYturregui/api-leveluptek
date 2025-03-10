<!-- Top navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark bg-primary" style="border-bottom: 1px solid #607d8b"
    id="navbar-main">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="{{ route('home') }}">
            {{ __('') }}
        </a>

        <!-- <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
      <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
      <li class="breadcrumb-item">
      <a href="#"><i class="fas fa-home"></i></a>
      </li>
      <li class="breadcrumb-item"><a href="#">Components</a></li>
      <li class="breadcrumb-item active" aria-current="page">Icons</li>
      </ol>
    </nav> -->

        <!-- User -->
        <ul class="navbar-nav align-items-center d-none d-md-flex">
            <li class="nav-item dropdown">
                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                            <img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/team-1-800x800.jpg" />
                        </span>
                        <div class="media-body ml-2 d-none d-lg-block">
                            <span class="mb-0 text-sm font-weight-bold">{{ auth()->user()->name }}</span>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Bienvenido!') }}</h6>
                    </div>
                    <a href="{{ route('home') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('Inicio') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Cerrar sesión') }}</span>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>

@if (!isset($hideSecondHeader))
<div class="header bg-primary pb-5 pt-2">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">{{ $pageTitle }}</h6>

                </div>
                @if (isset($route))
                <div class="col-lg-6 col-5 text-right">
                    <a href="{{ route($route.'_create') }}"" class=" btn btn-neutral">
                        <i class="fas fa-plus"></i>
                        Nuevo
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif