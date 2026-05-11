<nav class="layout-navbar container-fluid navbar navbar-expand-lg navbar-detached align-items-center bg-navbar-theme px-2 px-md-3">

    {{-- ☰ TOGGLE --}}
    <div class="layout-menu-toggle navbar-nav align-items-center me-2 d-lg-none">
        <a class="nav-item nav-link px-0" href="javascript:void(0)">
            <i class="fa fa-bars"></i>
        </a>
    </div>

    {{-- RIGHT SIDE --}}
    <div class="navbar-nav-right d-flex align-items-center w-100 justify-content-end">

        <ul class="navbar-nav flex-row align-items-center ms-auto gap-2">

            {{-- 🔔 NOTIFICATION --}}
            <li class="nav-item dropdown">
                <a class="nav-link position-relative"
                   href="javascript:void(0);"
                   data-bs-toggle="dropdown"
                   onclick="markAsRead()">

                    <i class="fa fa-bell"></i>

                    {{-- BADGE --}}
                    <span id="notif-count"
                          class="badge bg-danger position-absolute top-0 start-100 translate-middle p-1"
                          style="font-size:10px;">
                        0
                    </span>
                </a>

                {{-- 🔥 DROPDOWN FIX MOBILE --}}
                <ul class="dropdown-menu dropdown-menu-end p-0"
                    id="notif-list"
                    style="width:90vw; max-width:320px;">

                    <li class="dropdown-item text-center text-muted">
                        Loading...
                    </li>
                </ul>
            </li>

            {{-- 👤 USER --}}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center"
                   href="javascript:void(0);"
                   data-bs-toggle="dropdown">

                    <i class="fa fa-user-circle"></i>

                    {{-- 🔥 TEXT HIDE DI HP --}}
                    <div class="ms-2 d-none d-md-block text-start">
                        <div style="font-size:13px;">
                            {{ auth()->user()->name }}
                        </div>
                        <small class="text-muted">
                            {{ auth()->user()->role_name }}
                        </small>
                    </div>

                </a>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <a class="dropdown-item"
                           href="{{ route('profile.index') }}">
                            <i class="fa fa-user me-2"></i> Profil
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger">
                                <i class="fa fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </li>

                </ul>
            </li>

        </ul>

    </div>

</nav>