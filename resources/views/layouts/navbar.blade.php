<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme">

    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="fa fa-bars"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center w-100 justify-content-end">

        {{-- 🔔 NOTIFICATION --}}
        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <li class="nav-item dropdown me-3">
                <a class="nav-link dropdown-toggle hide-arrow"
                   href="javascript:void(0);"
                   data-bs-toggle="dropdown"
                   onclick="markAsRead()">

                    <i class="fa fa-bell fa-lg"></i>

                    <span id="notif-count"
                          class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                        0
                    </span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end"
                    id="notif-list"
                    style="min-width:300px;">
                    <li class="dropdown-item text-center text-muted">
                        Loading...
                    </li>
                </ul>
            </li>

            {{-- 👤 USER --}}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle hide-arrow d-flex align-items-center"
                   href="javascript:void(0);"
                   data-bs-toggle="dropdown">

                    <i class="fa fa-user-circle fa-lg me-2"></i>

                    <div class="text-start">
                        <div style="font-size:14px;">
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