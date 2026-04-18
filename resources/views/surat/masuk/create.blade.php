@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h5>Tambah Surat Masuk</h5>
    </div>

    <div class="card-body">

        {{-- 🔥 GLOBAL ERROR --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('surat-masuk.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row">

                {{-- NOMOR --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nomor Surat</label>
                    <input type="text" name="nomor_surat"
                           class="form-control @error('nomor_surat') is-invalid @enderror"
                           value="{{ old('nomor_surat') }}">

                    @error('nomor_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- TANGGAL SURAT --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Surat</label>
                    <input type="date" name="tanggal_surat"
                           class="form-control @error('tanggal_surat') is-invalid @enderror"
                           value="{{ old('tanggal_surat') }}">

                    @error('tanggal_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 🔥 TANGGAL MASUK --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk"
                           class="form-control @error('tanggal_masuk') is-invalid @enderror"
                           value="{{ old('tanggal_masuk', now()->format('Y-m-d')) }}">

                    @error('tanggal_masuk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- PENGIRIM --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Pengirim</label>
                    <input type="text" name="pengirim"
                           class="form-control @error('pengirim') is-invalid @enderror"
                           value="{{ old('pengirim') }}">

                    @error('pengirim')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- PERIHAL --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Perihal</label>
                    <input type="text" name="perihal"
                           class="form-control @error('perihal') is-invalid @enderror"
                           value="{{ old('perihal') }}">

                    @error('perihal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- SIFAT --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sifat</label>
                    <select name="sifat"
                            class="form-control @error('sifat') is-invalid @enderror">
                        <option value="">-- Pilih --</option>
                        <option value="biasa" {{ old('sifat') == 'biasa' ? 'selected' : '' }}>Biasa</option>
                        <option value="penting" {{ old('sifat') == 'penting' ? 'selected' : '' }}>Penting</option>
                        <option value="rahasia" {{ old('sifat') == 'rahasia' ? 'selected' : '' }}>Rahasia</option>
                    </select>

                    @error('sifat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- FILE --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">File PDF</label>
                    <input type="file" name="file_pdf"
                           class="form-control @error('file_pdf') is-invalid @enderror">

                    @error('file_pdf')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="mt-3">
                <button class="btn btn-primary">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </div>

        </form>

    </div>
</div>

@endsection