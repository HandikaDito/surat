@extends('layouts.app')

@section('content')
<div class="container">

    <h4>Disposisi</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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
            <a href="{{ route('disposition.index') }}" class="btn btn-secondary">
                Reset
            </a>
        </div>

    </form>

    {{-- FORM --}}
    <form method="POST" action="{{ route('disposition.store') }}" class="mb-3">
        @csrf

        <select name="surat_id" class="form-control mb-2" required>
            @foreach($surat as $s)
                <option value="{{ $s->id }}">{{ $s->nomor_surat }}</option>
            @endforeach
        </select>

        <select name="target_users[]" class="form-control mb-2" multiple required>
            @foreach($users as $u)
                @if(auth()->user()->canSendTo($u))
                    <option value="{{ $u->id }}">
                        {{ $u->name }} ({{ $u->jabatan ?? $u->role_name }})
                    </option>
                @endif
            @endforeach
        </select>

        <input type="date" name="deadline" class="form-control mb-2">

        <textarea name="catatan" class="form-control mb-2" placeholder="Instruksi"></textarea>

        <button class="btn btn-primary">Kirim Disposisi</button>
    </form>

    {{-- TABLE --}}
    <table class="table">
        <thead>
            <tr>
                <th>Surat</th>
                <th>Dari</th>
                <th>Ke</th>
                <th>Status</th>
                <th>Deadline</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse($dispositions as $item)
        <tr>

            <td>{{ $item->surat->nomor_surat ?? '-' }}</td>

            <td>
                {{ $item->fromUser->name ?? '-' }}<br>
                <small>{{ $item->fromUser->jabatan ?? $item->fromUser->role_name }}</small>
            </td>

            <td>
                @foreach($item->targets as $t)
                    {{ $t->user->name }} <br>
                @endforeach
            </td>

            <td>
                @foreach($item->targets as $t)
                    <span class="badge bg-{{ $t->status_color }}">
                        {{ $t->status_label }}
                    </span><br>
                @endforeach
            </td>

            <td>{{ $item->deadline ?? '-' }}</td>

            <td>
                <a href="{{ route('disposition.tracking', $item->id) }}"
                   class="btn btn-info btn-sm">Tracking</a>

                @php
                    $myTarget = $item->targets->firstWhere('user_id', auth()->id());
                @endphp

                @if($myTarget && $myTarget->status != 'done')
                    <button class="btn btn-warning btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#forwardModal{{ $item->id }}">
                        Forward
                    </button>

                    <button class="btn btn-success btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#doneModal{{ $item->id }}">
                        Done
                    </button>
                @endif
            </td>

        </tr>

        {{-- MODAL FORWARD --}}
        <div class="modal fade" id="forwardModal{{ $item->id }}">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('disposition.forward', $item->id) }}">
                    @csrf
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5>Forward Disposisi</h5>
                        </div>

                        <div class="modal-body">

                            <select name="target_users[]" class="form-control mb-2" multiple required>
                                @foreach($users as $u)
                                    @if(auth()->user()->canSendTo($u))
                                        <option value="{{ $u->id }}">
                                            {{ $u->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>

                            <textarea name="catatan" class="form-control"></textarea>

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-warning">Forward</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL DONE --}}
        <div class="modal fade" id="doneModal{{ $item->id }}">
            <div class="modal-dialog">
                <form method="POST"
                      action="{{ route('disposition.done', $item->id) }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="modal-content">

                        <div class="modal-header">
                            <h5>Selesaikan</h5>
                        </div>

                        <div class="modal-body">

                            <textarea name="keterangan" class="form-control mb-2"></textarea>

                            <input type="file" name="file" class="form-control">

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-success">Selesai</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        @empty
        <tr>
            <td colspan="6" class="text-center">Tidak ada data</td>
        </tr>
        @endforelse
        </tbody>
    </table>

    {{-- PAGINATION --}}
    <div class="mt-3">
        {{ $dispositions->links() }}
    </div>

</div>
@endsection