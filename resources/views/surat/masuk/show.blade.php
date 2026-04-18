@extends('layouts.app')

@section('content')
<div class="container">

    <h4>Detail Surat</h4>

    <div class="card p-3">

        <p><b>Nomor:</b> {{ $surat->nomor_surat }}</p>

        <p><b>Pengirim:</b> {{ $surat->pengirim }}</p>

        {{-- 🔥 TAMBAHAN --}}
        <p><b>Perihal:</b> {{ $surat->perihal }}</p>

        {{-- 🔥 TAMBAHAN --}}
        <p>
            <b>Sifat:</b> 
            <span class="badge 
                @if($surat->sifat == 'rahasia') bg-danger
                @elseif($surat->sifat == 'penting') bg-warning
                @else bg-secondary
                @endif">
                {{ ucfirst($surat->sifat) }}
            </span>
        </p>

        <p><b>Tanggal:</b> {{ $surat->tanggal_surat->format('d-m-Y') }}</p>

        <p><b>Status:</b> {{ $surat->status }}</p>

        <p><b>Creator:</b> {{ $surat->creator->name ?? '-' }}</p>

        @if($surat->file_pdf)
            <a href="{{ asset('storage/'.$surat->file_pdf) }}"
               target="_blank"
               class="btn btn-primary">
                <i class="fa fa-file-pdf"></i> Lihat PDF
            </a>
        @endif

    </div>

</div>
@endsection