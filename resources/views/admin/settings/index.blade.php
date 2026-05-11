@extends('layouts.app')

@section('content')

<div class="row">

    {{-- 🔥 FULL WIDTH DI HP --}}
    <div class="col-12 col-md-8 mx-auto">

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">⚙️ Pengaturan Sistem</h5>
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

                <form method="POST" action="{{ route('settings.update') }}">
                    @csrf
                    @method('PUT')

                    {{-- 🔥 GROUP: APLIKASI --}}
                    <h6 class="text-muted mt-2">Aplikasi</h6>

                    <div class="mb-3">
                        <label class="form-label">Nama Aplikasi</label>
                        <input type="text"
                               name="app_name"
                               class="form-control @error('app_name') is-invalid @enderror"
                               value="{{ $configs['app_name'] ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Instansi</label>
                        <input type="text"
                               name="company_name"
                               class="form-control"
                               value="{{ $configs['company_name'] ?? '' }}">
                    </div>

                    {{-- 🔥 GROUP: KONTAK --}}
                    <h6 class="text-muted mt-4">Kontak</h6>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ $configs['email'] ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text"
                               name="phone"
                               class="form-control"
                               value="{{ $configs['phone'] ?? '' }}">
                    </div>

                    {{-- 🔥 GROUP: SURAT --}}
                    <h6 class="text-muted mt-4">Pengaturan Surat</h6>

                    <div class="mb-3">
                        <label class="form-label">Prefix Nomor Surat</label>
                        <input type="text"
                               name="surat_prefix"
                               class="form-control @error('surat_prefix') is-invalid @enderror"
                               value="{{ $configs['surat_prefix'] ?? 'SK' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Max Upload (MB)</label>
                        <input type="number"
                               name="max_upload"
                               class="form-control @error('max_upload') is-invalid @enderror"
                               value="{{ $configs['max_upload'] ?? 2 }}">
                    </div>

                    {{-- 🔥 BUTTON RESPONSIVE --}}
                    <div class="d-grid d-md-flex justify-content-md-end mt-3">
                        <button class="btn btn-primary w-100 w-md-auto">
                            💾 Simpan Pengaturan
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

</div>

@endsection