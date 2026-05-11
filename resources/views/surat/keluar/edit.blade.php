@extends('layouts.app')

@section('content')

<div class="row">

    {{-- 🔥 FULL WIDTH DI HP --}}
    <div class="col-12 col-md-8 mx-auto">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Surat Keluar</h5>

                <a href="{{ route('surat-keluar.index') }}" class="btn btn-secondary btn-sm">
                    Kembali
                </a>
            </div>

            <div class="card-body">

                {{-- ERROR --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST"
                      action="{{ route('surat-keluar.update', $suratKeluar->id) }}"
                      enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <div class="row g-2">

                        {{-- NOMOR --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label">Nomor Surat</label>
                            <input type="text" name="nomor_surat"
                                   class="form-control @error('nomor_surat') is-invalid @enderror"
                                   value="{{ old('nomor_surat', $suratKeluar->nomor_surat) }}">
                        </div>

                        {{-- TANGGAL --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label">Tanggal Surat</label>
                            <input type="date" name="tanggal_surat"
                                   class="form-control @error('tanggal_surat') is-invalid @enderror"
                                   value="{{ old('tanggal_surat', $suratKeluar->tanggal_surat?->format('Y-m-d')) }}">
                        </div>

                        {{-- TUJUAN --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label">Tujuan</label>
                            <input type="text" name="tujuan"
                                   class="form-control @error('tujuan') is-invalid @enderror"
                                   value="{{ old('tujuan', $suratKeluar->tujuan) }}">
                        </div>

                        {{-- PERIHAL --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label">Perihal</label>
                            <input type="text" name="perihal"
                                   class="form-control @error('perihal') is-invalid @enderror"
                                   value="{{ old('perihal', $suratKeluar->perihal) }}">
                        </div>

                        {{-- ISI --}}
                        <div class="col-12">
                            <label class="form-label">Isi Surat</label>
                            <textarea name="isi_surat"
                                      rows="5"
                                      class="form-control @error('isi_surat') is-invalid @enderror">{{ old('isi_surat', $suratKeluar->isi_surat) }}</textarea>
                        </div>

                        {{-- FILE --}}
                        <div class="col-12">
                            <label class="form-label">File Surat (PDF)</label>

                            {{-- 🔥 FILE LAMA --}}
                            @if($suratKeluar->file_url)
                                <div class="mb-2">
                                    <a href="{{ $suratKeluar->file_url }}"
                                       target="_blank"
                                       class="btn btn-outline-primary w-100 w-md-auto">
                                        📄 Lihat File Lama
                                    </a>
                                </div>
                            @endif

                            <input type="file"
                                   name="file"
                                   class="form-control @error('file') is-invalid @enderror"
                                   accept="application/pdf">

                            <small class="text-muted">
                                Kosongkan jika tidak ingin mengganti file
                            </small>
                        </div>

                        {{-- BUTTON --}}
                        <div class="col-12 mt-2 d-grid d-md-flex gap-2">

                            <button class="btn btn-primary w-100 w-md-auto">
                                <i class="fa fa-save"></i> Update
                            </button>

                            <a href="{{ route('surat-keluar.index') }}"
                               class="btn btn-secondary w-100 w-md-auto">
                                Batal
                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection