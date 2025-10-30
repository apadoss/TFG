<header class="bg-white shadow-sm py-3 px-4 sticky-sm-top">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Sidebar + Logo -->
            <div class="d-flex align-items-center">
                <button id="sidebarToggle" class="btn me-2">
                    <i class="bi bi-list" style="font-size: 1.5rem;"></i>
                </button>

                <a href="{{ route('home') }}" class="text-decoration-none d-none d-md-block">
                    <img src="{{ asset('images/logos/logo.png') }}" alt="PCompare Logo" class="img-fluid" style="max-height: 40px;">
                </a>
            </div>
            
            <!-- Menú de usuario -->
            <div>
                @guest
                    <div class="d-flex align-items-center">
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary me-2">Iniciar sesión</a>
                        <a href="{{ route('register') }}" class="btn btn-sm btn-primary">Registro</a>
                    </div>
                @else
                    <div class="dropdown">
                        <button class="btn dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="avatar bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 32px; height: 32px;">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="userDropdown">
                            <li class="dropdown-header">
                                <div class="fw-bold">{{ Auth::user()->getFullNameAttribute() }}</div>
                                <div class="small text-muted">{{ Auth::user()->email }}</div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            {{-- <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>Mi perfil</a></li> --}}
                            <li><a class="dropdown-item" href="{{ route('configuraciones.index') }}"><i class="bi bi-pc-display me-2"></i>Mis configuraciones</a></li>
                            <li><a class="dropdown-item" href="{{ route('notifications.index') }}"><i class="bi bi-bell me-2"></i>Notificaciones de precios</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</header>
