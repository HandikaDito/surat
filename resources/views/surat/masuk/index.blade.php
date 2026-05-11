@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <div>
            <h3 class="fw-bold mb-1">Surat Masuk</h3>
            <small class="text-muted">
                Kelola surat masuk dan dokumen
            </small>
        </div>

        <a href="{{ route('surat-masuk.create') }}"
           class="btn btn-primary rounded-3 px-4">
            + Tambah Surat
        </a>

    </div>

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

                            <option value="">Semua Bulan</option>

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

                            <option value="">Semua Tahun</option>

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

    {{-- MOBILE --}}
    <div class="d-block d-md-none">

        @forelse($suratMasuk as $item)

            <div class="card shadow-sm border-0 rounded-4 mb-3">

                <div class="card-body">

                    <div class="mb-2">
                        <div class="fw-bold">
                            {{ $item->nomor_surat }}
                        </div>

                        <small class="text-muted">
                            {{ $item->pengirim }}
                        </small>
                    </div>

                    <div class="mb-2">
                        <span class="badge bg-{{ $item->status_color }} px-3 py-2 rounded-pill">
                            {{ $item->status_label }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">
                            Dibuat oleh: {{ $item->creator->name ?? '-' }}
                        </small>
                    </div>

                    <div class="d-grid gap-2">

                        <a href="{{ route('surat-masuk.show', $item->id) }}"
                           class="btn btn-info btn-sm">
                            Detail
                        </a>

                        @if($item->file_pdf)
                            <a href="{{ asset('storage/'.$item->file_pdf) }}"
                               target="_blank"
                               class="btn btn-primary btn-sm">
                                Lihat PDF
                            </a>
                        @endif

                        <form action="{{ route('surat-masuk.destroy', $item->id) }}"
                              method="POST">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm w-100"
                                    onclick="return confirm('Hapus surat?')">
                                Hapus
                            </button>
                        </form>

                    </div>

                </div>

            </div>

        @empty

            <div class="alert alert-secondary text-center">
                Tidak ada data surat
            </div>

        @endforelse

    </div>

    {{-- DESKTOP --}}
    <div class="card border-0 shadow-sm rounded-4 d-none d-md-block">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>
                        <th>No</th>
                        <th>Nomor Surat</th>
                        <th>Pengirim</th>
                        <th>Status</th>
                        <th>Creator</th>
                        <th width="280">Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($suratMasuk as $item)

                    <tr>

                        <td>
                            {{ ($suratMasuk->currentPage() - 1) * $suratMasuk->perPage() + $loop->iteration }}
                        </td>

                        <td class="fw-semibold">
                            {{ $item->nomor_surat }}
                        </td>

                        <td>
                            {{ $item->pengirim }}
                        </td>

                        <td>
                            <span class="badge bg-{{ $item->status_color }} px-3 py-2 rounded-pill">
                                {{ $item->status_label }}
                            </span>
                        </td>

                        <td>
                            {{ $item->creator->name ?? '-' }}
                        </td>

                        <td>

                            <div class="d-flex gap-2 flex-wrap">

                                <a href="{{ route('surat-masuk.show', $item->id) }}"
                                   class="btn btn-info btn-sm">
                                    Detail
                                </a>

                                @if($item->file_pdf)
                                    <a href="{{ asset('storage/'.$item->file_pdf) }}"
                                       target="_blank"
                                       class="btn btn-primary btn-sm">
                                        PDF
                                    </a>
                                @endif

                                <form action="{{ route('surat-masuk.destroy', $item->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Hapus surat?')">
                                        Hapus
                                    </button>
                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="6" class="text-center py-4">
                            Tidak ada data surat
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="p-3">
            {{ $suratMasuk->links() }}
        </div>

    </div>

</div>
@endsection