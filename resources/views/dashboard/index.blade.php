@extends('layouts.app')

@section('content')

@php
    $hour = now()->format('H');

    if ($hour >= 5 && $hour < 10) {
        $greeting = 'Selamat pagi';
        $emoji = '☀️';
    } elseif ($hour >= 10 && $hour < 15) {
        $greeting = 'Selamat siang';
        $emoji = '🌤️';
    } elseif ($hour >= 15 && $hour < 18) {
        $greeting = 'Selamat sore';
        $emoji = '🌇';
    } else {
        $greeting = 'Selamat malam';
        $emoji = '🌙';
    }
@endphp

<div class="container-fluid">

    {{-- HERO --}}
    <div class="dashboard-hero mb-4">

        <div>
            <h2 class="fw-bold mb-2">
                {{ $greeting }}, {{ auth()->user()->name }} {{ $emoji }}
            </h2>

            <p class="mb-0 text-muted">
                {{ auth()->user()->jabatan ?? auth()->user()->role_name }}
            </p>
        </div>

        <div class="hero-badge">
            {{ now()->translatedFormat('l, d F Y') }}
        </div>

    </div>

    {{-- STATISTICS --}}
    <div class="row g-4">

        {{-- TOTAL --}}
        <div class="col-6 col-md-3">
            <div class="stat-card stat-primary">

                <div class="stat-icon">
                    <i class="fa fa-envelope"></i>
                </div>

                <div>
                    <div class="stat-label">Total Surat</div>
                    <div class="stat-value">{{ $suratMasuk }}</div>
                </div>

            </div>
        </div>

        {{-- PROSES --}}
        <div class="col-6 col-md-3">
            <div class="stat-card stat-warning">

                <div class="stat-icon">
                    <i class="fa fa-spinner"></i>
                </div>

                <div>
                    <div class="stat-label">Sedang Diproses</div>
                    <div class="stat-value">{{ $suratProses }}</div>
                </div>

            </div>
        </div>

        {{-- SELESAI --}}
        <div class="col-6 col-md-3">
            <div class="stat-card stat-success">

                <div class="stat-icon">
                    <i class="fa fa-check-circle"></i>
                </div>

                <div>
                    <div class="stat-label">Selesai</div>
                    <div class="stat-value">{{ $suratSelesai }}</div>
                </div>

            </div>
        </div>

        {{-- DISPOSISI --}}
        <div class="col-6 col-md-3">
            <div class="stat-card stat-info">

                <div class="stat-icon">
                    <i class="fa fa-paper-plane"></i>
                </div>

                <div>
                    <div class="stat-label">Disposisi Aktif</div>
                    <div class="stat-value">{{ $aktif }}</div>

                    @if($unread > 0)
                        <small class="text-danger fw-semibold">
                            {{ $unread }} belum dibaca
                        </small>
                    @endif
                </div>

            </div>
        </div>

    </div>

    {{-- CHART --}}
    <div class="modern-chart-card mt-4">

        <div class="chart-header">

            <div>
                <h5 class="fw-bold mb-1">
                    Statistik Surat
                </h5>

                <small class="text-muted">
                    Surat masuk & keluar tahun {{ now()->year }}
                </small>
            </div>

            <div class="chart-badge">
                Tahun {{ now()->year }}
            </div>

        </div>

        <div class="chart-body">
            <div id="chart"></div>
        </div>

    </div>

</div>

@endsection

@section('styles')

<style>

.dashboard-hero{
    background: linear-gradient(135deg, #ffffff, #f7f9fc);
    border-radius: 22px;
    padding: 24px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    flex-wrap:wrap;
}

.hero-badge{
    background:#eef2ff;
    color:#696cff;
    padding:10px 18px;
    border-radius:30px;
    font-size:13px;
    font-weight:600;
}

.stat-card{
    border-radius:22px;
    padding:22px;
    display:flex;
    align-items:center;
    gap:16px;
    height:100%;
    background:#fff;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
    transition:.25s ease;
}

.stat-card:hover{
    transform:translateY(-4px);
}

.stat-icon{
    width:58px;
    height:58px;
    border-radius:18px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
    color:#fff;
    flex-shrink:0;
}

.stat-primary .stat-icon{
    background:#696cff;
}

.stat-warning .stat-icon{
    background:#ffab00;
}

.stat-success .stat-icon{
    background:#71dd37;
}

.stat-info .stat-icon{
    background:#03c3ec;
}

.stat-label{
    font-size:13px;
    color:#6c757d;
    margin-bottom:4px;
}

.stat-value{
    font-size:28px;
    font-weight:700;
    line-height:1;
}

.modern-chart-card{
    background:#fff;
    border-radius:22px;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
    overflow:hidden;
}

.chart-header{
    padding:22px 24px;
    border-bottom:1px solid #f0f0f0;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:16px;
    flex-wrap:wrap;
}

.chart-badge{
    background:#f8f9fb;
    padding:10px 16px;
    border-radius:30px;
    font-size:13px;
    font-weight:600;
}

.chart-body{
    padding:20px;
}

@media(max-width:768px){

    .dashboard-hero{
        padding:18px;
    }

    .stat-card{
        padding:16px;
        border-radius:18px;
        gap:12px;
    }

    .stat-icon{
        width:48px;
        height:48px;
        font-size:18px;
        border-radius:14px;
    }

    .stat-value{
        font-size:22px;
    }

    .chart-header{
        padding:16px;
    }

    .chart-body{
        padding:10px;
    }

}

</style>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>

var options = {
    chart: {
        type: 'bar',
        height: window.innerWidth < 768 ? 300 : 420,
        toolbar: {
            show: false
        }
    },

    series: [
        {
            name: 'Surat Masuk',
            data: @json($chartMasuk)
        },
        {
            name: 'Surat Keluar',
            data: @json($chartKeluar)
        }
    ],

    xaxis: {
        categories: @json($months),
        labels: {
            rotate: window.innerWidth < 768 ? -45 : 0
        }
    },

    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: window.innerWidth < 768 ? '65%' : '50%',
            borderRadius: 8,
            borderRadiusApplication: 'end'
        }
    },

    dataLabels: {
        enabled: false
    },

    stroke: {
        show: false
    },

    colors: ['#696cff', '#71dd37'],

    legend: {
        position: window.innerWidth < 768 ? 'bottom' : 'top',
        horizontalAlign: 'right'
    },

    grid: {
        borderColor: '#f0f0f0',
        strokeDashArray: 4
    },

    tooltip: {
        y: {
            formatter: function(val) {
                return val + " surat";
            }
        }
    },

    responsive: [{
        breakpoint: 768,
        options: {
            chart: {
                height: 300
            }
        }
    }]
};

var chart = new ApexCharts(
    document.querySelector("#chart"),
    options
);

chart.render();

</script>

@endsection