@extends('layouts.app')

@section('content')

<div class="row">

    {{-- 🔥 CENTER + RESPONSIVE --}}
    <div class="col-12 col-md-8 mx-auto">

        <div class="card shadow">

            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Detail Surat Keluar</span>

                <a href="{{ route('surat-keluar.index') }}"
                   class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="card-body">

                {{-- 🔥 GRID 2 KOLOM --}}
                <div class="row g-3">

                    <div class="col-12 col-md-6">
                        <label class="fw-bold">Nomor Surat</label>
                        <div>{{ $suratKeluar->nomor_surat }}</div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="fw-bold">Tanggal Surat</label>
                        <div>{{ $suratKeluar->tanggal_surat?->format('d M Y') }}</div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="fw-bold">Tujuan</label>
                        <div class="word-break">{{ $suratKeluar->tujuan }}</div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="fw-bold">Perihal</label>
                        <div class="word-break">{{ $suratKeluar->perihal }}</div>
                    </div>

                    <div class="col-12">
                        <label class="fw-bold">Isi Surat</label>
                        <div class="border p-3 rounded bg-light word-break"
                             style="white-space: pre-line;">
                            {{ $suratKeluar->isi_surat }}
                        </div>
                    </div>

                    {{-- FILE --}}
                    <div class="col-12 col-md-6">
                        <label class="fw-bold">File Surat</label>
                        <div>
                            @if($suratKeluar->file_url)
                                <a href="{{ $suratKeluar->file_url }}"
                                   target="_blank"
                                   class="btn btn-outline-primary w-100 w-md-auto">
                                    <i class="fa fa-file-pdf"></i> Lihat PDF
                                </a>
                            @else
                                <span class="text-muted">Tidak ada file</span>
                            @endif
                        </div>
                    </div>

                    {{-- PEMBUAT --}}
                    <div class="col-12 col-md-6">
                        <label class="fw-bold">Dibuat Oleh</label>
                        <div>{{ $suratKeluar->creator->name ?? '-' }}</div>
                    </div>

                    {{-- STATUS --}}
                    <div class="col-12 col-md-6">
                        <label class="fw-bold">Status</label>
                        <div>
                            <span class="badge bg-{{ $suratKeluar->status_color }}">
                                {{ $suratKeluar->status_label }}
                            </span>
                        </div>
                    </div>

                    {{-- APPROVER --}}
                    <div class="col-12 col-md-6">
                        <label class="fw-bold">Disetujui Oleh</label>
                        <div>
                            @if($suratKeluar->approver)
                                {{ $suratKeluar->approver->name }}<br>
                                <small class="text-muted">
                                    {{ $suratKeluar->approved_at?->format('d M Y H:i') }}
                                </small>
                            @else
                                <span class="text-muted">Belum disetujui</span>
                            @endif
                        </div>
                    </div>

                    {{-- CREATED --}}
                    <div class="col-12">
                        <label class="fw-bold">Dibuat Pada</label>
                        <div>
                            {{ $suratKeluar->created_at?->translatedFormat('d M Y H:i') }}
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection