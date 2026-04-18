<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>E-Disposisi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- 🔥 SNEAT CSS (FIX PATH SESUAI FOLDER KAMU) --}}
    <link rel="stylesheet" href="{{ asset('sneat/vendor/css/core.css') }}">
    <link rel="stylesheet" href="{{ asset('sneat/vendor/css/theme-default.css') }}">
    <link rel="stylesheet" href="{{ asset('sneat/css/demo.css') }}">

    {{-- ICON --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    {{-- OPTIONAL CSS --}}
    @yield('styles')
</head>

<body>

<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

        {{-- 🔥 SIDEBAR --}}
        @include('layouts.sidebar')

        <div class="layout-page">

            {{-- 🔥 NAVBAR --}}
            @include('layouts.navbar')

            <div class="content-wrapper">

                {{-- 🔥 MAIN CONTENT --}}
                <div class="container-xxl flex-grow-1 container-p-y">

                    {{-- ALERT --}}
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @yield('content')

                </div>

                {{-- FOOTER --}}
                @include('layouts.footer')

                <div class="content-backdrop fade"></div>
            </div>
        </div>
    </div>
</div>

{{-- 🔥 SNEAT JS --}}
<script src="{{ asset('sneat/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('sneat/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('sneat/js/main.js') }}"></script>

{{-- 🔔 GLOBAL NOTIFICATION SCRIPT --}}
<script>
async function loadNotif(){
    try {
        let res = await fetch('/notif');
        let data = await res.json();

        let countEl = document.getElementById('notif-count');
        let listEl  = document.getElementById('notif-list');

        if (!countEl || !listEl) return;

        countEl.innerText = data.count;

        let html = '';

        if (data.notifications.length === 0) {
            html = `<li class="dropdown-item text-center text-muted">
                        Tidak ada notifikasi
                    </li>`;
        } else {
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

    } catch (e) {
        console.error('Notif error:', e);
    }
}

async function markAsRead(){
    try {
        await fetch('/notif/read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        loadNotif();
    } catch (e) {
        console.error('Mark read error:', e);
    }
}

// load pertama
loadNotif();

// auto refresh
setInterval(loadNotif, 5000);
</script>

{{-- 🔥 SCRIPT KHUSUS HALAMAN --}}
@yield('scripts')

</body>
</html>