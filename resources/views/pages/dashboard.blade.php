@extends('layout.main')

@push('style')
    <link rel="stylesheet" href="{{asset('sneat/vendor/libs/apex-charts/apex-charts.css')}}" />

    <style>
        body {
            background-color: #f5f7fb;
        }

        .card {
            border: none !important;
            border-radius: 14px !important;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
        }

        .dashboard-title {
            font-weight: 700;
        }

        .sub-text {
            font-size: 13px;
            color: #888;
        }
    </style>
@endpush

@push('script')
    <script src="{{asset('sneat/vendor/libs/apex-charts/apexcharts.js')}}"></script>
    <script>
        const options = {
            chart: {
                type: 'bar',
                toolbar: { show: false }
            },
            series: [{
                name: '{{ __('dashboard.letter_transaction') }}',
                data: [{{ $todayIncomingLetter }},{{ $todayOutgoingLetter }},{{ $todayDispositionLetter }}]
            }],
            colors: ['#696cff'],
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '40%'
                }
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: [
                    '{{ __('dashboard.incoming_letter') }}',
                    '{{ __('dashboard.outgoing_letter') }}',
                    '{{ __('dashboard.disposition_letter') }}',
                ],
            }
        }

        const chart = new ApexCharts(document.querySelector("#today-graphic"), options);
        chart.render();
    </script>
@endpush

@section('content')

    <!-- HEADER -->
    <div class="mb-4">
        <h4 class="dashboard-title">👋 {{ $greeting }}</h4>
        <small class="text-muted">{{ $currentDate }}</small>
    </div>

    <div class="row g-4">

        <!-- LEFT SIDE -->
        <div class="col-lg-8">

            <!-- HERO CARD -->
            <div class="card shadow-sm mb-4 overflow-hidden">
                <div class="row align-items-center">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="fw-bold text-primary mb-2">
                                Sistem Disposisi Surat
                            </h5>
                            <p class="sub-text mb-2">
                                Monitoring surat masuk, keluar, dan disposisi secara real-time.
                            </p>
                            <small class="text-muted">
                                *) {{ __('dashboard.today_report') }}
                            </small>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center">
                        <img src="{{asset('sneat/img/man-with-laptop-light.png')}}"
                             class="img-fluid p-3"
                             style="max-height:140px;">
                    </div>
                </div>
            </div>

            <!-- CHART -->
            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h6 class="fw-bold mb-1">{{ __('dashboard.today_graphic') }}</h6>
                            <span class="badge bg-label-primary">{{ __('dashboard.today') }}</span>
                        </div>

                        <div>
                            @if($percentageLetterTransaction > 0)
                                <span class="text-success fw-semibold">
                                    <i class="bx bx-up-arrow-alt"></i> {{ $percentageLetterTransaction }}%
                                </span>
                            @elseif($percentageLetterTransaction < 0)
                                <span class="text-danger fw-semibold">
                                    <i class="bx bx-down-arrow-alt"></i> {{ $percentageLetterTransaction }}%
                                </span>
                            @endif
                        </div>
                    </div>

                    <h2 class="fw-bold mb-3">{{ $todayLetterTransaction }}</h2>

                    <div id="today-graphic"></div>

                </div>
            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="col-lg-4">
            <div class="row g-4">

                <div class="col-6">
                    <x-dashboard-card-simple
                        :label="__('dashboard.incoming_letter')"
                        :value="$todayIncomingLetter"
                        :daily="true"
                        color="success"
                        icon="bx-envelope"
                        :percentage="$percentageIncomingLetter"
                    />
                </div>

                <div class="col-6">
                    <x-dashboard-card-simple
                        :label="__('dashboard.outgoing_letter')"
                        :value="$todayOutgoingLetter"
                        :daily="true"
                        color="danger"
                        icon="bx-envelope"
                        :percentage="$percentageOutgoingLetter"
                    />
                </div>

                <div class="col-6">
                    <x-dashboard-card-simple
                        :label="__('dashboard.disposition_letter')"
                        :value="$todayDispositionLetter"
                        :daily="true"
                        color="primary"
                        icon="bx-envelope"
                        :percentage="$percentageDispositionLetter"
                    />
                </div>

                <div class="col-6">
                    <x-dashboard-card-simple
                        :label="__('dashboard.active_user')"
                        :value="$activeUser"
                        :daily="false"
                        color="info"
                        icon="bx-user-check"
                        :percentage="0"
                    />
                </div>

            </div>
        </div>

    </div>

@endsection
