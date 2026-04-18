@extends('layouts.app')

@section('content')

@php
    $hour = now()->format('H');

    if ($hour >= 5 && $hour < 12) {
        $greeting = 'Selamat pagi';
    } elseif ($hour >= 12 && $hour < 15) {
        $greeting = 'Selamat siang';
    } elseif ($hour >= 15 && $hour < 18) {
        $greeting = 'Selamat sore';
    } else {
        $greeting = 'Selamat malam';
    }
@endphp

{{-- WELCOME --}}
<div class="mb-3">
    <h4>{{ $greeting }}, {{ auth()->user()->name }} 👋</h4>
    <small class="text-muted">
        {{ auth()->user()->jabatan ?? auth()->user()->role_name }}
    </small>
</div>

{{-- ================= CARD ================= --}}
<div class="row">

    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white shadow">
            <div class="card-body">
                <h6>Total Surat Masuk</h6>
                <h3>{{ $suratMasuk }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white shadow">
            <div class="card-body">
                <h6>Dalam Proses</h6>
                <h3>{{ $suratProses }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white shadow">
            <div class="card-body">
                <h6>Selesai</h6>
                <h3>{{ $suratSelesai }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white shadow">
            <div class="card-body">
                <h6>Disposisi Saya</h6>
                <h3>{{ $aktif + $unread }}</h3>
            </div>
        </div>
    </div>

</div>

{{-- ================= CHART ================= --}}
<div class="card mt-3 shadow">
    <div class="card-header">
        <b>Statistik Surat (Masuk vs Keluar)</b>
    </div>
    <div class="card-body">
        <div id="chart"></div>
    </div>
</div>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
var options = {
    chart: {
        type: 'bar', // 🔥 BAR CHART
        height: 350,
        animations: {
            enabled: true,
            easing: 'easeinout',
            speed: 800
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
        categories: @json($months)
    },

    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '55%',
            borderRadius: 6
        }
    },

    dataLabels: {
        enabled: false
    },

    colors: ['#0d6efd', '#198754'],

    legend: {
        position: 'top'
    },

    tooltip: {
        y: {
            formatter: function(val) {
                return val + " surat";
            }
        }
    },

    grid: {
        borderColor: '#e7e7e7'
    }
};

var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();
</script>

@endsection