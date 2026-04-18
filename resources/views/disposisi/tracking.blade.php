@extends('layouts.app')

@section('content')
<div class="container">

<h4 class="mb-3">Tracking Disposisi</h4>

{{-- 🔍 FILTER (OPSIONAL) --}}
<form method="GET" class="row mb-3">

    {{-- BULAN --}}
    <div class="col-md-3">
        <select name="bulan" class="form-control">
            <option value="">Semua Bulan</option>

            @for($i=1; $i<=12; $i++)
                <option value="{{ $i }}"
                    {{ request('bulan') == $i ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                </option>
            @endfor
        </select>
    </div>

    {{-- TAHUN --}}
    <div class="col-md-3">
        <select name="tahun" class="form-control">
            <option value="">Semua Tahun</option>

            @for($y = now()->year; $y >= 2020; $y--)
                <option value="{{ $y }}"
                    {{ request('tahun') == $y ? 'selected' : '' }}>
                    {{ $y }}
                </option>
            @endfor
        </select>
    </div>

    <div class="col-md-4">
        <button class="btn btn-primary">Filter</button>
        <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
    </div>

</form>

{{-- 🔥 STATUS TERAKHIR --}}
@if($last)
<div class="mb-3">
    <span class="badge bg-success">
        Status Terakhir: {{ $last->targets->first()->status_label ?? '-' }}
    </span>
</div>
@endif

{{-- 🔥 TIMELINE --}}
@forelse($histories as $item)

<div class="card mb-3 p-3">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between">

        <div>
            <strong>{{ $item->fromUser->name ?? 'System' }}</strong>
            <br>
            <small class="text-muted">
                {{ $item->fromUser->jabatan ?? $item->fromUser->role_name }}
            </small>
        </div>

        <small class="text-muted">
            {{ $item->created_at->format('d M Y H:i') }}
        </small>

    </div>

    <hr>

    {{-- TARGET --}}
    <div>
        @foreach($item->targets as $t)
            <div>
                → {{ $t->user->name }}
                <span class="badge bg-{{ $t->status_color }}">
                    {{ $t->status_label }}
                </span>
            </div>
        @endforeach
    </div>

    {{-- CATATAN --}}
    @if($item->catatan)
        <div class="mt-2">
            <strong>Catatan:</strong><br>
            {{ $item->catatan }}
        </div>
    @endif

    {{-- LAPORAN --}}
    @if($item->reports->count())
        <div class="mt-3">
            <strong>Laporan:</strong>

            @foreach($item->reports as $r)

                <div class="mt-2">

                    {{-- IMAGE --}}
                    @if($r->file_type == 'image')
                        <img src="{{ asset('storage/'.$r->file_path) }}"
                             width="200"
                             class="img-thumbnail">

                    {{-- VIDEO --}}
                    @elseif($r->file_type == 'video')
                        <video width="250" controls>
                            <source src="{{ asset('storage/'.$r->file_path) }}">
                        </video>

                    {{-- PDF --}}
                    @else
                        <a href="{{ asset('storage/'.$r->file_path) }}"
                           target="_blank"
                           class="btn btn-outline-primary btn-sm">
                            📄 Lihat PDF
                        </a>
                    @endif

                    <br>
                    <small class="text-muted">
                        {{ $r->user->name ?? '-' }} -
                        {{ $r->created_at->format('d M Y H:i') }}
                    </small>

                </div>

            @endforeach
        </div>
    @endif

</div>

@empty
<div class="alert alert-secondary text-center">
    Tidak ada histori disposisi
</div>
@endforelse

</div>
@endsection