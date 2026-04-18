@extends('layouts.app')

@section('content')

<div class="card">

<div class="card-header d-flex justify-content-between align-items-center">
    <span>Surat Keluar</span>

    <a href="{{ route('surat-keluar.create') }}"
       class="btn btn-primary btn-sm">
        <i class="fa fa-plus"></i> Tambah
    </a>
</div>

<div class="card-body">

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
        <a href="{{ route('surat-keluar.index') }}" class="btn btn-secondary">
            Reset
        </a>
    </div>

</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No Surat</th>
            <th>Perihal</th>
            <th>Pembuat</th>
            <th>Status</th>
            <th width="220">Aksi</th>
        </tr>
    </thead>

    <tbody>
        @forelse($data as $s)
        <tr>
            <td>{{ $s->nomor_surat }}</td>

            <td>{{ $s->perihal }}</td>

            <td>{{ $s->creator->name ?? '-' }}</td>

            {{-- STATUS --}}
            <td>
                <span class="badge bg-{{ $s->status_color }}">
                    {{ $s->status_label }}
                </span>
            </td>

            <td>

                {{-- 👁️ VIEW --}}
                <a href="{{ route('surat-keluar.show', $s->id) }}"
                   class="btn btn-info btn-sm">
                    <i class="fa fa-eye"></i>
                </a>

                {{-- DRAFT → SUBMIT --}}
                @if($s->isDraft() && $s->created_by == auth()->id())
                    <form action="{{ route('surat-keluar.submit', $s->id) }}"
                          method="POST"
                          style="display:inline;">
                        @csrf
                        <button class="btn btn-warning btn-sm">
                            Submit
                        </button>
                    </form>
                @endif

                {{-- REVIEW → APPROVE / REJECT --}}
                @if($s->isReview() && in_array(auth()->user()->role_level, [1,2]))

                    <form action="{{ route('surat-keluar.approve', $s->id) }}"
                          method="POST"
                          style="display:inline;">
                        @csrf
                        <button class="btn btn-success btn-sm">
                            Approve
                        </button>
                    </form>

                    <form action="{{ route('surat-keluar.reject', $s->id) }}"
                          method="POST"
                          style="display:inline;">
                        @csrf
                        <button class="btn btn-danger btn-sm">
                            Reject
                        </button>
                    </form>

                @endif

                {{-- DELETE --}}
                @if($s->created_by == auth()->id())
                <form action="{{ route('surat-keluar.destroy', $s->id) }}"
                      method="POST"
                      style="display:inline;">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger btn-sm"
                            onclick="return confirm('Hapus surat ini?')">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>
                @endif

            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">Tidak ada data</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3">
    {{ $data->links() }}
</div>

</div>
</div>

@endsection