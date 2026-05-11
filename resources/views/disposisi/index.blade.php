@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="fw-bold mb-1">Disposisi Surat</h3>
            <small class="text-muted">
                Kelola disposisi surat dan monitoring tindak lanjut
            </small>
        </div>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3 align-items-end">

                    <div class="col-6 col-md-3">
                        <label class="form-label fw-semibold">
                            Bulan
                        </label>

                        <select name="bulan" class="form-select">

                            <option value="">
                                Semua Bulan
                            </option>

                            @for($i=1; $i<=12; $i++)
                                <option value="{{ $i }}"
                                    {{ request('bulan') == $i ? 'selected' : '' }}>

                                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}

                                </option>
                            @endfor

                        </select>
                    </div>

                    <div class="col-6 col-md-3">

                        <label class="form-label fw-semibold">
                            Tahun
                        </label>

                        <select name="tahun" class="form-select">

                            <option value="">
                                Semua Tahun
                            </option>

                            @for($y = now()->year; $y >= 2020; $y--)
                                <option value="{{ $y }}"
                                    {{ request('tahun') == $y ? 'selected' : '' }}>

                                    {{ $y }}

                                </option>
                            @endfor

                        </select>
                    </div>

                    <div class="col-12 col-md-3">

                        <button class="btn btn-primary w-100 rounded-3">
                            Filter Data
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- FORM DISPOSISI --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-primary text-white rounded-top-4 border-0 py-3">
            <h5 class="mb-0 fw-semibold">
                Buat Disposisi
            </h5>
        </div>

        <div class="card-body p-4">

            <form method="POST" action="{{ route('disposition.store') }}">
                @csrf

                <div class="row g-3">

                    {{-- SURAT --}}
                    <div class="col-12 col-md-6">

                        <label class="form-label fw-semibold">
                            Pilih Surat
                        </label>

                        <select name="surat_id"
                                class="form-select"
                                required>

                            <option value="">
                                -- Pilih Surat --
                            </option>

                            @foreach($surat as $s)
                                <option value="{{ $s->id }}">
                                    {{ $s->nomor_surat }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    {{-- TUJUAN --}}
                    <div class="col-12 col-md-6">

                        <label class="form-label fw-semibold">
                            Tujuan Disposisi
                        </label>

                        <select name="target_users[]"
                                class="form-select"
                                multiple
                                size="5"
                                required>

                            @foreach($users as $u)
                                @if(auth()->user()->canSendTo($u))
                                    <option value="{{ $u->id }}">
                                        {{ $u->name }}
                                    </option>
                                @endif
                            @endforeach

                        </select>

                        <small class="text-muted">
                            Tekan CTRL untuk memilih lebih dari satu user
                        </small>

                    </div>

                    {{-- DEADLINE --}}
                    <div class="col-12 col-md-4">

                        <label class="form-label fw-semibold">
                            Deadline
                        </label>

                        <input type="date"
                               name="deadline"
                               class="form-control">

                    </div>

                    {{-- CATATAN --}}
                    <div class="col-12">

                        <label class="form-label fw-semibold">
                            Instruksi / Catatan
                        </label>

                        <textarea name="catatan"
                                  rows="4"
                                  class="form-control"
                                  placeholder="Masukkan instruksi disposisi..."></textarea>

                    </div>

                    {{-- BUTTON --}}
                    <div class="col-12 text-end">

                        <button class="btn btn-primary px-4 rounded-3">
                            Kirim Disposisi
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-semibold">
                Data Disposisi
            </h5>
        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Surat</th>
                        <th>Dari</th>
                        <th>Tujuan</th>
                        <th>Status</th>
                        <th>Deadline</th>
                        <th width="220">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($dispositions as $item)

                    <tr>

                        <td class="fw-semibold">
                            {{ $item->surat->nomor_surat ?? '-' }}
                        </td>

                        <td>
                            <div class="fw-semibold">
                                {{ $item->fromUser->name ?? '-' }}
                            </div>

                            <small class="text-muted">
                                {{ $item->fromUser->jabatan ?? '' }}
                            </small>
                        </td>

                        <td>
                            @foreach($item->targets as $t)
                                <div class="mb-1">
                                    {{ $t->user->name }}
                                </div>
                            @endforeach
                        </td>

                        <td>
                            @foreach($item->targets as $t)
                                <span class="badge bg-{{ $t->status_color }} px-3 py-2 rounded-pill mb-1">
                                    {{ $t->status_label }}
                                </span>
                            @endforeach
                        </td>

                        <td>
                            {{ $item->deadline ?? '-' }}
                        </td>

                        <td>

                            <div class="d-flex flex-column flex-md-row gap-2">

                                {{-- FIX TRACKING PER SURAT --}}
                                <a href="{{ route('disposition.tracking', $item->surat_id) }}"
                                   class="btn btn-info btn-sm">
                                    Tracking
                                </a>

                                @php
                                    $myTarget = $item->targets->firstWhere('user_id', auth()->id());
                                @endphp

                                @if($myTarget && $myTarget->status != 'done')

                                    <button class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#forwardModal{{ $item->id }}">
                                        Forward
                                    </button>

                                    <button class="btn btn-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#doneModal{{ $item->id }}">
                                        Done
                                    </button>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center py-4">
                            Tidak ada data disposisi
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $dispositions->links() }}
    </div>

</div>

@endsection