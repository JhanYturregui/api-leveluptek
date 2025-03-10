<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button
            class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#sidenav-collapse-main"
            aria-controls="sidenav-main"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="{{ route('home') }}">
            <img src="{{ asset('argon') }}/img/brand/blue.png" class="navbar-brand-img" alt="..." />
        </a>
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                            <img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/team-1-800x800.jpg" />
                        </span>
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
                    <a
                        href="{{ route('logout') }}"
                        class="dropdown-item"
                        onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Cerrar sesión') }}</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('argon') }}/img/brand/blue.png" />
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button
                            type="button"
                            class="navbar-toggler"
                            data-toggle="collapse"
                            data-target="#sidenav-collapse-main"
                            aria-controls="sidenav-main"
                            aria-expanded="false"
                            aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Navigation -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="fas fa-home"></i>
                        {{ __('INICIO') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link active"
                        href="#navbar-examples"
                        data-toggle="collapse"
                        role="button"
                        aria-expanded="true"
                        aria-controls="navbar-examples">
                        <i class="fab fa-laravel" style="color: #5e72e4"></i>
                        <span class="nav-link-text">{{ __('ADMINISTRACIÓN') }}</span>
                    </a>

                    <div class="collapse show" id="navbar-examples">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('categories') }}">
                                    <i class="fas fa-list"></i>
                                    {{ __('Categorías') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('products') }}">
                                    <i class="fas fa-list"></i>
                                    {{ __('Productos') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('suppliers') }}">
                                    <i class="fas fa-list"></i>
                                    {{ __('Proveedores') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customers') }}">
                                    <i class="fas fa-list"></i>
                                    {{ __('Clientes') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link active"
                        href="#navbar-examples"
                        data-toggle="collapse"
                        role="button"
                        aria-expanded="true"
                        aria-controls="navbar-examples">
                        <i class="fab fa-laravel" style="color: #5e72e4"></i>
                        <span class="nav-link-text">{{ __('CAJA') }}</span>
                    </a>

                    <div class="collapse show" id="navbar-examples">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('purchases') }}">
                                    <i class="fas fa-list"></i>
                                    {{ __('Compras') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('sales') }}">
                                    <i class="fas fa-list"></i>
                                    {{ __('Ventas') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('cash_transactions') }}">
                                    <i class="fas fa-list"></i>
                                    {{ __('Movimientos de Caja') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>