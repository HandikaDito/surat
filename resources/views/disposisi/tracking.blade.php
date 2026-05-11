@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <div>
            <h3 class="fw-bold mb-1">
                Tracking Surat
            </h3>

            <small class="text-muted">
                Perjalanan disposisi surat secara lengkap
            </small>
        </div>

        <a href="{{ route('disposition.index') }}"
           class="btn btn-secondary rounded-3 px-4">
            <i class="fa fa-arrow-left me-1"></i>
            Kembali
        </a>

    </div>

    {{-- INFO SURAT --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body">

            <div class="row g-4">

                <div class="col-12 col-md-4">
                    <small class="text-muted d-block">
                        Nomor Surat
                    </small>

                    <div class="fw-semibold fs-5">
                        {{ $surat->nomor_surat }}
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <small class="text-muted d-block">
                        Pengirim
                    </small>

                    <div class="fw-semibold">
                        {{ $surat->pengirim }}
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <small class="text-muted d-block">
                        Status Surat
                    </small>

                    <span class="badge bg-{{ $surat->status_color }} px-3 py-2 rounded-pill">
                        {{ $surat->status_label }}
                    </span>
                </div>

                <div class="col-12">
                    <small class="text-muted d-block">
                        Perihal
                    </small>

                    <div class="fw-semibold word-break">
                        {{ $surat->perihal }}
                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- TIMELINE --}}
    <div class="timeline-wrapper">

        @forelse($histories as $item)

            <div class="timeline-item">

                <div class="timeline-dot"></div>

                <div class="timeline-card">

                    {{-- HEADER --}}
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">

                        <div>
                            <div class="fw-bold">
                                {{ $item->fromUser->name ?? 'System' }}
                            </div>

                            <small class="text-muted">
                                {{ $item->fromUser->jabatan ?? $item->fromUser->role_name ?? '-' }}
                            </small>
                        </div>

                        <small class="text-muted">
                            {{ $item->created_at->format('d M Y H:i') }}
                        </small>

                    </div>

                    {{-- TARGET --}}
                    <div class="mt-3">

                        <small class="text-muted d-block mb-2">
                            Diteruskan ke:
                        </small>

                        @foreach($item->targets as $t)

                            <div class="target-row">

                                <div>
                                    <div class="fw-semibold">
                                        {{ $t->user->name }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $t->user->jabatan ?? '-' }}
                                    </small>
                                </div>

                                <span class="badge bg-{{ $t->status_color }} px-3 py-2 rounded-pill">
                                    {{ $t->status_label }}
                                </span>

                            </div>

                        @endforeach

                    </div>

                    {{-- CATATAN --}}
                    @if($item->catatan)
                        <div class="mt-3">

                            <small class="text-muted d-block mb-2">
                                Catatan
                            </small>

                            <div class="note-box">
                                {{ $item->catatan }}
                            </div>

                        </div>
                    @endif

                    {{-- LAPORAN --}}
                    @if($item->reports->count())

                        <div class="mt-4">

                            <small class="text-muted d-block mb-3">
                                Laporan Tindak Lanjut
                            </small>

                            @foreach($item->reports as $r)

                                <div class="report-box">

                                    @if($r->file_type == 'image')

                                        <img src="{{ asset('storage/'.$r->file_path) }}"
                                             class="img-fluid rounded-4">

                                    @elseif($r->file_type == 'video')

                                        <video class="w-100 rounded-4" controls>
                                            <source src="{{ asset('storage/'.$r->file_path) }}">
                                        </video>

                                    @else

                                        <a href="{{ asset('storage/'.$r->file_path) }}"
                                           target="_blank"
                                           class="btn btn-outline-primary">
                                            📄 Lihat Dokumen
                                        </a>

                                    @endif

                                    <small class="text-muted d-block mt-2">
                                        {{ $r->user->name ?? '-' }}
                                        •
                                        {{ $r->created_at->format('d M Y H:i') }}
                                    </small>

                                </div>

                            @endforeach

                        </div>

                    @endif

                </div>

            </div>

        @empty

            <div class="alert alert-secondary text-center">
                Tidak ada histori disposisi
            </div>

        @endforelse

    </div>

</div>

@endsection

@section('styles')

<style>

.timeline-wrapper{
    position:relative;
    padding-left:20px;
}

.timeline-wrapper::before{
    content:'';
    position:absolute;
    left:10px;
    top:0;
    bottom:0;
    width:3px;
    background:#e9ecef;
}

.timeline-item{
    position:relative;
    margin-bottom:30px;
}

.timeline-dot{
    width:20px;
    height:20px;
    border-radius:50%;
    background:#696cff;
    position:absolute;
    left:-1px;
    top:18px;
    z-index:2;
}

.timeline-card{
    margin-left:40px;
    background:#fff;
    border-radius:20px;
    padding:22px;
    box-shadow:0 8px 24px rgba(0,0,0,0.05);
}

.target-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:12px 0;
    border-bottom:1px solid #f1f1f1;
    gap:12px;
}

.target-row:last-child{
    border-bottom:none;
}

.note-box{
    background:#f8f9fb;
    padding:16px;
    border-radius:14px;
    word-break:break-word;
}

.report-box{
    background:#fafafa;
    border-radius:16px;
    padding:16px;
    margin-bottom:14px;
}

@media(max-width:768px){

    .timeline-card{
        margin-left:28px;
        padding:16px;
    }

    .timeline-dot{
        width:16px;
        height:16px;
        left:2px;
    }

    .target-row{
        flex-direction:column;
        align-items:flex-start;
    }

}

</style>

@endsection