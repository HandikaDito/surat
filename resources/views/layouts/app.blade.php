<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>E-Disposisi</title>

    {{-- MOBILE --}}
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    {{-- SNEAT CSS --}}
    <link rel="stylesheet"
          href="{{ asset('sneat/vendor/css/core.css') }}">

    <link rel="stylesheet"
          href="{{ asset('sneat/vendor/css/theme-default.css') }}">

    <link rel="stylesheet"
          href="{{ asset('sneat/css/demo.css') }}">

    {{-- FONT AWESOME --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
          rel="stylesheet">

    {{-- SELECT2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
          rel="stylesheet" />

    {{-- GLOBAL STYLE --}}
    <style>

        :root{
            --radius:18px;
        }

        body{
            overflow-x:hidden;
            background:#f5f7fb;
            font-family:'Public Sans', sans-serif;
        }

        /* ================= TYPOGRAPHY ================= */

        h1,h2,h3,h4,h5,h6{
            font-weight:700;
            color:#2b2c34;
        }

        /* ================= CONTAINER ================= */

        .container-fluid{
            padding-left:20px !important;
            padding-right:20px !important;
        }

        /* ================= CARD ================= */

        .card{
            border:none !important;
            border-radius:var(--radius) !important;
            box-shadow:0 2px 12px rgba(0,0,0,0.05);
            overflow:hidden;
            background:#fff;
        }

        .card-header{
            background:#fff;
            border-bottom:1px solid #f0f0f0;
            padding:18px 22px;
        }

        .card-body{
            padding:24px;
        }

        /* ================= FORM ================= */

        .form-control,
        .form-select{
            min-height:46px;
            border-radius:12px !important;
            border:1px solid #dfe3e8;
        }

        .form-control:focus,
        .form-select:focus{
            border-color:#696cff;
            box-shadow:none;
        }

        textarea.form-control{
            min-height:auto;
        }

        /* ================= BUTTON ================= */

        .btn{
            border-radius:12px !important;
            font-weight:500;
            padding:10px 16px;
        }

        .btn-sm{
            padding:7px 12px;
        }

        /* ================= BADGE ================= */

        .badge{
            padding:8px 12px;
            border-radius:30px;
            font-size:11px;
        }

        /* ================= TABLE ================= */

        .table{
            margin-bottom:0;
        }

        .table-responsive{
            border-radius:16px;
        }

        .table th{
            background:#f8f9fb;
            font-weight:600;
            white-space:nowrap;
        }

        .table td,
        .table th{
            vertical-align:middle;
            padding:14px;
        }

        /* ================= ALERT ================= */

        .alert{
            border:none;
            border-radius:14px;
        }

        /* ================= WORD BREAK ================= */

        .word-break{
            word-break:break-word;
        }

        /* ================= SIDEBAR ================= */

        #layout-menu{
            transition:all .3s ease;
        }

        #sidebar-overlay{
            position:fixed;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background:rgba(0,0,0,0.4);
            z-index:998;
            display:none;
        }

        /* ================= NAVBAR ================= */

        .layout-navbar{
            backdrop-filter:blur(10px);
            background:rgba(255,255,255,0.92) !important;
        }

        /* ================= PAGINATION ================= */

        .pagination{
            gap:6px;
        }

        .page-link{
            border:none;
            border-radius:10px !important;
            color:#696cff;
        }

        .page-item.active .page-link{
            background:#696cff;
        }

        /* ================= MOBILE ================= */

        @media(max-width:768px){

            .container-fluid{
                padding-left:14px !important;
                padding-right:14px !important;
            }

            .card-body{
                padding:18px !important;
            }

            h3{
                font-size:20px;
            }

            h4,h5{
                font-size:16px;
            }

            .btn{
                width:100%;
            }

            .table{
                min-width:700px;
                font-size:13px;
            }

            .badge{
                font-size:10px;
            }

            /* sidebar mobile */
            #layout-menu{
                position:fixed;
                z-index:999;
                left:-260px;
                width:260px;
                height:100%;
            }

            body.sidebar-open #layout-menu{
                left:0;
            }

            body.sidebar-open #sidebar-overlay{
                display:block;
            }

        }

    </style>

    @yield('styles')

</head>

<body class="layout-navbar-fixed layout-menu-fixed">

<div class="layout-wrapper layout-content-navbar">

    <div class="layout-container">

        {{-- SIDEBAR --}}
        @include('layouts.sidebar')

        {{-- OVERLAY --}}
        <div id="sidebar-overlay"></div>

        {{-- PAGE --}}
        <div class="layout-page">

            {{-- NAVBAR --}}
            @include('layouts.navbar')

            {{-- CONTENT --}}
            <div class="content-wrapper">

                <div class="container-fluid px-3 px-md-4 flex-grow-1 container-p-y">

                    {{-- SUCCESS --}}
                    @if(session('success'))

                        <div class="alert alert-success alert-dismissible fade show shadow-sm">

                            {{ session('success') }}

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert"></button>

                        </div>

                    @endif

                    {{-- ERROR --}}
                    @if(session('error'))

                        <div class="alert alert-danger alert-dismissible fade show shadow-sm">

                            {{ session('error') }}

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert"></button>

                        </div>

                    @endif

                    {{-- CONTENT --}}
                    @yield('content')

                </div>

                {{-- FOOTER --}}
                @include('layouts.footer')

                <div class="content-backdrop fade"></div>

            </div>

        </div>

    </div>

</div>

{{-- JQUERY --}}
<script src="{{ asset('sneat/vendor/libs/jquery/jquery.js') }}"></script>

{{-- BOOTSTRAP --}}
<script src="{{ asset('sneat/vendor/js/bootstrap.js') }}"></script>

{{-- SNEAT --}}
<script src="{{ asset('sneat/js/main.js') }}"></script>

{{-- SELECT2 --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- SIDEBAR MOBILE --}}
<script>

function toggleSidebar() {
    document.body.classList.toggle('sidebar-open');
}

document.addEventListener("DOMContentLoaded", function () {

    const toggleBtn = document.querySelector('.layout-menu-toggle a');
    const overlay = document.getElementById('sidebar-overlay');

    // hamburger
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            toggleSidebar();
        });
    }

    // overlay
    if (overlay) {
        overlay.addEventListener('click', function () {
            toggleSidebar();
        });
    }

    // auto close mobile
    document.querySelectorAll('#layout-menu a').forEach(link => {

        link.addEventListener('click', () => {

            if(window.innerWidth < 768){
                document.body.classList.remove('sidebar-open');
            }

        });

    });

});

</script>

{{-- NOTIFICATION --}}
<script>

async function loadNotif(){

    try{

        let res = await fetch('/notif');
        let data = await res.json();

        let countEl = document.getElementById('notif-count');
        let listEl  = document.getElementById('notif-list');

        if(!countEl || !listEl) return;

        countEl.innerText = data.count;

        let html = '';

        if(data.notifications.length === 0){

            html = `
                <li class="dropdown-item text-center text-muted">
                    Tidak ada notifikasi
                </li>
            `;

        }else{

            data.notifications.forEach(n => {

                html += `
                    <li>

                        <a class="dropdown-item ${n.is_read ? '' : 'fw-bold'}"
                           href="${n.url ?? '#'}">

                            <div>

                                <strong>${n.title}</strong><br>

                                <small>${n.message}</small>

                            </div>

                        </a>

                    </li>
                `;

            });

        }

        listEl.innerHTML = html;

    }catch(e){

        console.error('Notif error:', e);

    }

}

async function markAsRead(){

    try{

        await fetch('/notif/read', {
            method:'POST',
            headers:{
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            }
        });

        loadNotif();

    }catch(e){

        console.error('Mark read error:', e);

    }

}

// load awal
loadNotif();

// auto refresh
setInterval(loadNotif, 5000);

</script>

@yield('scripts')

</body>
</html>