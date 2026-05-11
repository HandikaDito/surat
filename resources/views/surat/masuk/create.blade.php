@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <div>
            <h3 class="fw-bold mb-1">Tambah Surat Masuk</h3>
            <small class="text-muted">
                Input data surat masuk beserta dokumen PDF
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

                    {{-- ERROR --}}
                    @if ($errors->any())

                        <div class="alert alert-danger rounded-4 shadow-sm">

                            <div class="fw-semibold mb-2">
                                Ada kesalahan input:
                            </div>

                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>

                        </div>

                    @endif

                    <form method="POST"
                          action="{{ route('surat-masuk.store') }}"
                          enctype="multipart/form-data">

                        @csrf

                        <div class="row g-4">

                            {{-- NOMOR --}}
                            <div class="col-12 col-md-6">

                                <label class="form-label fw-semibold">
                                    Nomor Surat
                                </label>

                                <input type="text"
                                       name="nomor_surat"
                                       class="form-control @error('nomor_surat') is-invalid @enderror"
                                       value="{{ old('nomor_surat') }}"
                                       placeholder="Masukkan nomor surat">

                            </div>

                            {{-- TANGGAL SURAT --}}
                            <div class="col-12 col-md-6">

                                <label class="form-label fw-semibold">
                                    Tanggal Surat
                                </label>

                                <input type="date"
                                       name="tanggal_surat"
                                       class="form-control @error('tanggal_surat') is-invalid @enderror"
                                       value="{{ old('tanggal_surat') }}">

                            </div>

                            {{-- TANGGAL MASUK --}}
                            <div class="col-12 col-md-6">

                                <label class="form-label fw-semibold">
                                    Tanggal Masuk
                                </label>

                                <input type="date"
                                       name="tanggal_masuk"
                                       class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                       value="{{ old('tanggal_masuk', now()->format('Y-m-d')) }}">

                            </div>

                            {{-- PENGIRIM --}}
                            <div class="col-12 col-md-6">

                                <label class="form-label fw-semibold">
                                    Pengirim
                                </label>

                                <input type="text"
                                       name="pengirim"
                                       class="form-control @error('pengirim') is-invalid @enderror"
                                       value="{{ old('pengirim') }}"
                                       placeholder="Masukkan nama pengirim">

                            </div>

                            {{-- PERIHAL --}}
                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Perihal
                                </label>

                                <input type="text"
                                       name="perihal"
                                       class="form-control @error('perihal') is-invalid @enderror"
                                       value="{{ old('perihal') }}"
                                       placeholder="Masukkan perihal surat">

                            </div>

                            {{-- SIFAT --}}
                            <div class="col-12 col-md-6">

                                <label class="form-label fw-semibold">
                                    Sifat Surat
                                </label>

                                <select name="sifat"
                                        class="form-select @error('sifat') is-invalid @enderror">

                                    <option value="">
                                        -- Pilih Sifat --
                                    </option>

                                    <option value="biasa"
                                        {{ old('sifat') == 'biasa' ? 'selected' : '' }}>
                                        Biasa
                                    </option>

                                    <option value="penting"
                                        {{ old('sifat') == 'penting' ? 'selected' : '' }}>
                                        Penting
                                    </option>

                                    <option value="rahasia"
                                        {{ old('sifat') == 'rahasia' ? 'selected' : '' }}>
                                        Rahasia
                                    </option>

                                </select>

                            </div>

                            {{-- FILE --}}
                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Upload File Surat
                                </label>

                                <div class="border rounded-4 p-4 bg-light">

                                    <input type="file"
                                           name="file"
                                           class="form-control @error('file') is-invalid @enderror"
                                           accept="application/pdf">

                                    <small class="text-muted d-block mt-2">
                                        Format PDF • Maksimal 2MB
                                    </small>

                                </div>

                            </div>

                            {{-- BUTTON --}}
                            <div class="col-12">

                                <div class="d-grid d-md-flex justify-content-end gap-2">

                                    <a href="{{ route('surat-masuk.index') }}"
                                       class="btn btn-secondary px-4">
                                        Batal
                                    </a>

                                    <button class="btn btn-primary px-4">
                                        <i class="fa fa-save me-1"></i>
                                        Simpan Surat
                                    </button>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection