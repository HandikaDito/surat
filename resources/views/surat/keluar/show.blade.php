@extends('layouts.app')

@section('content')

<div class="container">

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Detail Surat Keluar</span>

            <a href="{{ route('surat-keluar.index') }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card-body">

            {{-- NOMOR --}}
            <div class="mb-3">
                <label class="fw-bold">Nomor Surat</label>
                <div>{{ $suratKeluar->nomor_surat }}</div>
            </div>

            {{-- PERIHAL --}}
            <div class="mb-3">
                <label class="fw-bold">Perihal</label>
                <div>{{ $suratKeluar->perihal }}</div>
            </div>

            {{-- ISI --}}
            <div class="mb-3">
                <label class="fw-bold">Isi Surat</label>
                <div class="border p-3 rounded bg-light">
                    {{ $suratKeluar->isi_surat }}
                </div>
            </div>

            {{-- PEMBUAT --}}
            <div class="mb-3">
                <label class="fw-bold">Dibuat Oleh</label>
                <div>{{ $suratKeluar->creator->name ?? '-' }}</div>
            </div>

            {{-- STATUS --}}
            <div class="mb-3">
                <label class="fw-bold">Status</label>
                <div>
                    <span class="badge bg-{{ $suratKeluar->status_color }}">
                        {{ $suratKeluar->status_label }}
                    </span>
                </div>
            </div>

            {{-- WAKTU --}}
            <div class="mb-3">
                <label class="fw-bold">Dibuat Pada</label>
                <div>
                    {{ $suratKeluar->created_at?->translatedFormat('d M Y H:i') }}
                </div>
            </div>

        </div>

    </div>

</div>

@endsection