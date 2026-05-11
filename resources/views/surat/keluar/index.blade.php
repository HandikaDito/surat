@extends('layouts.app')

@section('content')

<div class="card">

<div class="card-header d-flex justify-content-between align-items-center">
    <span>Surat Keluar</span>

    <a href="{{ route('surat-keluar.create') }}"
       class="btn btn-primary btn-sm">
        <i class="fa fa-plus"></i> Tambah
    </a>
</div>

<div class="card-body">

{{-- 🔍 FILTER --}}
<form method="GET" class="row g-2 mb-3">

    <div class="col-6 col-md-3">
        <select name="bulan" class="form-control">
            <option value="">Bulan</option>
            @for($i=1; $i<=12; $i++)
                <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                </option>
            @endfor
        </select>
    </div>

    <div class="col-6 col-md-3">
        <select name="tahun" class="form-control">
            <option value="">Tahun</option>
            @for($y = now()->year; $y >= 2020; $y--)
                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                    {{ $y }}
                </option>
            @endfor
        </select>
    </div>

    <div class="col-12 col-md-4">
        <button class="btn btn-primary w-100 w-md-auto">Filter</button>
    </div>

</form>

{{-- ================= MOBILE CARD ================= --}}
<div class="d-block d-md-none">

@forelse($data as $s)

<div class="card mb-2 shadow-sm">
    <div class="card-body">

        <strong>{{ $s->nomor_surat }}</strong><br>

        <small class="text-muted">
            {{ $s->tujuan }}
        </small>

        <div class="mt-2">
            {{ $s->perihal }}
        </div>

        <div class="mt-2">
            <span class="badge bg-{{ $s->status_color }}">
                {{ $s->status_label }}
            </span>
        </div>

        <div class="mt-2 d-flex flex-wrap gap-1">

            <a href="{{ route('surat-keluar.show', $s->id) }}"
               class="btn btn-info btn-sm w-100">
                View
            </a>

            @if($s->isDraft() && $s->created_by == auth()->id())
                <form action="{{ route('surat-keluar.submit', $s->id) }}" method="POST" class="w-100">
                    @csrf
                    <button class="btn btn-warning btn-sm w-100">Submit</button>
                </form>
            @endif

        </div>

    </div>
</div>

@empty
<div class="alert alert-secondary text-center">
    Tidak ada data
</div>
@endforelse

</div>

{{-- ================= DESKTOP TABLE ================= --}}
<div class="table-responsive d-none d-md-block">

<table class="table table-bordered align-middle">

    <thead>
        <tr>
            <th>No Surat</th>
            <th>Tujuan</th>
            <th>Perihal</th>
            <th>Pembuat</th>
            <th>Status</th>
            <th>File</th>
            <th>Approved</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>

    @foreach($data as $s)
    <tr>

        <td>{{ $s->nomor_surat }}</td>
        <td>{{ $s->tujuan }}</td>
        <td>{{ $s->perihal }}</td>
        <td>{{ $s->creator->name ?? '-' }}</td>

        <td>
            <span class="badge bg-{{ $s->status_color }}">
                {{ $s->status_label }}
            </span>
        </td>

        <td>
            @if($s->file_url)
                <a href="{{ $s->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    PDF
                </a>
            @endif
        </td>

        <td>
            {{ $s->approver->name ?? '-' }}
        </td>

        <td>

            <a href="{{ route('surat-keluar.show', $s->id) }}"
               class="btn btn-info btn-sm">View</a>

        </td>

    </tr>
    @endforeach

    </tbody>

</table>

</div>

{{-- PAGINATION --}}
<div class="mt-3">
    {{ $data->links() }}
</div>

</div>
</div>

@endsection