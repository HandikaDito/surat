@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <div>
            <h3 class="fw-bold mb-1">
                Detail Disposisi
            </h3>

            <small class="text-muted">
                Informasi lengkap disposisi surat
            </small>
        </div>

        <a href="{{ route('disposition.index') }}"
           class="btn btn-secondary rounded-3">

            ← Kembali

        </a>

    </div>

    {{-- CARD --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            {{-- PERIHAL --}}
            <div class="mb-4">

                <label class="text-muted small mb-1">
                    PERIHAL SURAT
                </label>

                <div class="fw-semibold fs-5 word-break">
                    {{ $data->surat->perihal ?? '-' }}
                </div>

            </div>

            {{-- INFO --}}
            <div class="row g-4 mb-4">

                {{-- DARI --}}
                <div class="col-12 col-md-6">

                    <div class="border rounded-4 p-3 h-100 bg-light">

                        <label class="text-muted small">
                            DARI
                        </label>

                        <div class="fw-semibold mt-1">
                            {{ $data->fromUser->name }}
                        </div>

                        <small class="text-muted">
                            {{ $data->fromUser->jabatan ?? '-' }}
                        </small>

                    </div>

                </div>

                {{-- DEADLINE --}}
                <div class="col-12 col-md-6">

                    <div class="border rounded-4 p-3 h-100 bg-light">

                        <label class="text-muted small">
                            DEADLINE
                        </label>

                        <div class="fw-semibold mt-1">

                            {{ $data->deadline
                                ? \Carbon\Carbon::parse($data->deadline)->translatedFormat('d F Y')
                                : '-' }}

                        </div>

                    </div>

                </div>

            </div>

            {{-- TUJUAN --}}
            <div class="mb-4">

                <label class="text-muted small mb-2 d-block">
                    TUJUAN DISPOSISI
                </label>

                <div class="row g-3">

                    @foreach($data->targets as $t)

                        <div class="col-12 col-md-6">

                            <div class="border rounded-4 p-3 d-flex justify-content-between align-items-center bg-white shadow-sm">

                                <div>

                                    <div class="fw-semibold">
                                        {{ $t->user->name }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $t->user->jabatan ?? '-' }}
                                    </small>

                                </div>

                                <span class="badge bg-{{ $t->status_color }} px-3 py-2 rounded-pill">

                                    {{ $t->status_label }}

                                </span>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

            {{-- CATATAN --}}
            <div class="mb-4">

                <label class="text-muted small mb-2 d-block">
                    CATATAN / INSTRUKSI
                </label>

                <div class="border rounded-4 p-4 bg-light word-break">

                    {{ $data->catatan ?? 'Tidak ada catatan' }}

                </div>

            </div>

            {{-- FILE --}}
            @if($data->surat->file_path)

                <div class="mt-4">

                    <a href="{{ asset('storage/'.$data->surat->file_path) }}"
                       target="_blank"
                       class="btn btn-primary rounded-3 px-4">

                        📄 Lihat File Surat

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection