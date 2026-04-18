<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    {{-- LOGO --}}
    <div class="app-brand demo">
        <a href="{{ url('/') }}" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bold">E-Disposisi</span>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        {{-- DASHBOARD --}}
        <li class="menu-item {{ request()->is('/') ? 'active' : '' }}">
            <a href="{{ url('/') }}" class="menu-link">
                <i class="menu-icon tf-icons fa fa-home"></i>
                <div>Dashboard</div>
            </a>
        </li>

        {{-- SURAT MASUK --}}
        <li class="menu-item {{ request()->is('surat-masuk*') ? 'active' : '' }}">
            <a href="{{ url('/surat-masuk') }}" class="menu-link">
                <i class="menu-icon tf-icons fa fa-envelope"></i>
                <div>Surat Masuk</div>
            </a>
        </li>

        {{-- SURAT KELUAR --}}
        <li class="menu-item {{ request()->is('surat-keluar*') ? 'active' : '' }}">
            <a href="{{ url('/surat-keluar') }}" class="menu-link">
                <i class="menu-icon tf-icons fa fa-paper-plane"></i>
                <div>Surat Keluar</div>
            </a>
        </li>

        {{-- DISPOSISI --}}
        <li class="menu-item {{ request()->is('disposisi*') ? 'active' : '' }}">
            <a href="{{ url('/disposisi') }}" class="menu-link">
                <i class="menu-icon tf-icons fa fa-share"></i>
                <div>Disposisi</div>
            </a>
        </li>

        {{-- ADMIN ONLY --}}
        @if(auth()->user()->role_level == 0)

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Admin</span>
            </li>

            <li class="menu-item {{ request()->is('user*') ? 'active' : '' }}">
                <a href="{{ url('/user') }}" class="menu-link">
                    <i class="menu-icon tf-icons fa fa-users"></i>
                    <div>Manajemen User</div>
                </a>
            </li>

            <li class="menu-item {{ request()->is('settings*') ? 'active' : '' }}">
                <a href="{{ url('/settings') }}" class="menu-link">
                    <i class="menu-icon tf-icons fa fa-cog"></i>
                    <div>Settings</div>
                </a>
            </li>

        @endif

    </ul>
</aside>