@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <div>
            <h3 class="fw-bold mb-1">Detail Surat Masuk</h3>
            <small class="text-muted">
                Informasi lengkap surat masuk
            </small>
        </div>

        <a href="{{ route('surat-masuk.index') }}"
           class="btn btn-secondary rounded-3 px-4">
            <i class="fa fa-arrow-left me-1"></i>
            Kembali
        </a>

    </div>

    <div class="row justify-content-center">

        <div class="col-12 col-xl-10">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    {{-- TOP INFO --}}
                    <div class="row g-4 mb-4">

                        <div class="col-12 col-md-6">
                            <div class="border rounded-4 p-3 bg-light h-100">
                                <label class="text-muted small mb-1">
                                    NOMOR SURAT
                                </label>

                                <div class="fw-semibold fs-5">
                                    {{ $surat->nomor_surat }}
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded-4 p-3 bg-light h-100">
                                <label class="text-muted small mb-1">
                                    PENGIRIM
                                </label>

                                <div class="fw-semibold word-break">
                                    {{ $surat->pengirim }}
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- DETAIL --}}
                    <div class="row g-4 mb-4">

                        <div class="col-12 col-md-6">
                            <div class="border rounded-4 p-3 h-100">

                                <label class="text-muted small">
                                    TANGGAL SURAT
                                </label>

                                <div class="fw-semibold mt-2">
                                    {{ $surat->tanggal_surat?->format('d M Y') ?? '-' }}
                                </div>

                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded-4 p-3 h-100">

                                <label class="text-muted small">
                                    STATUS
                                </label>

                                <div class="mt-2">
                                    <span class="badge bg-{{ $surat->status_color }} px-3 py-2 rounded-pill">
                                        {{ $surat->status_label }}
                                    </span>
                                </div>

                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded-4 p-3 h-100">

                                <label class="text-muted small">
                                    SIFAT SURAT
                                </label>

                                <div class="mt-2">

                                    <span class="badge
                                        @if($surat->sifat == 'rahasia')
                                            bg-danger
                                        @elseif($surat->sifat == 'penting')
                                            bg-warning
                                        @else
                                            bg-secondary
                                        @endif
                                        px-3 py-2 rounded-pill">

                                        {{ ucfirst($surat->sifat ?? '-') }}

                                    </span>

                                </div>

                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded-4 p-3 h-100">

                                <label class="text-muted small">
                                    CREATOR
                                </label>

                                <div class="fw-semibold mt-2">
                                    {{ $surat->creator->name ?? '-' }}
                                </div>

                            </div>
                        </div>

                    </div>

                    {{-- PERIHAL --}}
                    <div class="mb-4">

                        <label class="text-muted small mb-2 d-block">
                            PERIHAL
                        </label>

                        <div class="border rounded-4 p-4 bg-light word-break">

                            {{ $surat->perihal ?? '-' }}

                        </div>

                    </div>

                    {{-- FILE --}}
                    <div>

                        <label class="text-muted small mb-2 d-block">
                            FILE SURAT
                        </label>

                        @if($surat->file_url)

                            <div class="d-grid d-md-inline">

                                <a href="{{ $surat->file_url }}"
                                   target="_blank"
                                   class="btn btn-primary rounded-3 px-4">

                                    <i class="fa fa-file-pdf me-2"></i>
                                    Lihat PDF Surat

                                </a>

                            </div>

                        @else

                            <div class="alert alert-secondary mb-0">
                                Tidak ada file surat
                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection