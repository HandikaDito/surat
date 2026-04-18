@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">⚙️ Pengaturan Sistem</h5>
    </div>

    <div class="card-body">

        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            @method('PUT')

            {{-- 🔹 APP NAME --}}
            <div class="mb-3">
                <label class="form-label">Nama Aplikasi</label>
                <input type="text"
                       name="app_name"
                       class="form-control @error('app_name') is-invalid @enderror"
                       value="{{ $configs['app_name'] ?? '' }}">

                @error('app_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- 🔹 COMPANY --}}
            <div class="mb-3">
                <label class="form-label">Nama Instansi</label>
                <input type="text"
                       name="company_name"
                       class="form-control"
                       value="{{ $configs['company_name'] ?? '' }}">
            </div>

            {{-- 🔹 EMAIL --}}
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email"
                       name="email"
                       class="form-control"
                       value="{{ $configs['email'] ?? '' }}">
            </div>

            {{-- 🔹 PHONE --}}
            <div class="mb-3">
                <label class="form-label">No. Telepon</label>
                <input type="text"
                       name="phone"
                       class="form-control"
                       value="{{ $configs['phone'] ?? '' }}">
            </div>

            {{-- 🔹 PREFIX --}}
            <div class="mb-3">
                <label class="form-label">Prefix Nomor Surat</label>
                <input type="text"
                       name="surat_prefix"
                       class="form-control @error('surat_prefix') is-invalid @enderror"
                       value="{{ $configs['surat_prefix'] ?? 'SK' }}">

                @error('surat_prefix')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- 🔹 MAX UPLOAD --}}
            <div class="mb-3">
                <label class="form-label">Max Upload (MB)</label>
                <input type="number"
                       name="max_upload"
                       class="form-control @error('max_upload') is-invalid @enderror"
                       value="{{ $configs['max_upload'] ?? 2 }}">

                @error('max_upload')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary">
                💾 Simpan Pengaturan
            </button>

        </form>

    </div>
</div>

@endsection