@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h5>Tambah Surat Keluar</h5>
    </div>

    <div class="card-body">

        {{-- 🔥 ERROR GLOBAL --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('surat-keluar.store') }}">
            @csrf

            <div class="row">

                {{-- NOMOR SURAT --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nomor Surat</label>
                    <input type="text" name="nomor_surat"
                           class="form-control @error('nomor_surat') is-invalid @enderror"
                           value="{{ old('nomor_surat') }}">

                    @error('nomor_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- TANGGAL --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Surat</label>
                    <input type="date" name="tanggal_surat"
                           class="form-control @error('tanggal_surat') is-invalid @enderror"
                           value="{{ old('tanggal_surat') }}">

                    @error('tanggal_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 🔥 TUJUAN (INI YANG KAMU MAU) --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tujuan Surat</label>
                    <input type="text" name="tujuan"
                           class="form-control @error('tujuan') is-invalid @enderror"
                           placeholder="Contoh: PT ABC / Dinas XYZ"
                           value="{{ old('tujuan') }}">

                    @error('tujuan')
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

                {{-- ISI SURAT --}}
                <div class="col-md-12 mb-3">
                    <label class="form-label">Isi Surat</label>
                    <textarea name="isi_surat"
                              rows="6"
                              class="form-control @error('isi_surat') is-invalid @enderror"
                              placeholder="Tulis isi surat di sini...">{{ old('isi_surat') }}</textarea>

                    @error('isi_surat')
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