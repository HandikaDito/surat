@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Surat Masuk</h4>

    <a href="{{ route('surat-masuk.create') }}" class="btn btn-primary mb-3">
        + Tambah Surat
    </a>

    {{-- 🔍 FILTER --}}
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

        {{-- BUTTON --}}
        <div class="col-md-4">
            <button class="btn btn-primary">Filter</button>
            <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary">
                Reset
            </a>
        </div>

    </form>

    <div class="card">
        <div class="table-responsive">

            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor</th>
                        <th>Pengirim</th>
                        <th>Status</th>
                        <th>Creator</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($suratMasuk as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nomor_surat }}</td>
                        <td>{{ $item->pengirim }}</td>

                        {{-- STATUS --}}
                        <td>
                            <span class="badge bg-{{ $item->status_color }}">
                                {{ $item->status_label }}
                            </span>
                        </td>

                        <td>{{ $item->creator->name ?? '-' }}</td>

                        <td>
                            <a href="{{ route('surat-masuk.show', $item->id) }}"
                               class="btn btn-info btn-sm">
                                Detail
                            </a>

                            <form action="{{ route('surat-masuk.destroy', $item->id) }}"
                                  method="POST"
                                  style="display:inline">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus surat?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            Tidak ada data
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        {{-- PAGINATION --}}
        <div class="p-3">
            {{ $suratMasuk->links() }}
        </div>

    </div>

</div>
@endsection