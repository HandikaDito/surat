@extends('layouts.app')

@section('content')

<div class="card">
<div class="card-header">Detail Disposisi</div>

<div class="card-body">

<p><strong>Surat:</strong> {{ $data->surat->perihal ?? '-' }}</p>

<p><strong>Dari:</strong> {{ $data->fromUser->name }}</p>

<p><strong>Kepada:</strong></p>
<ul>
@foreach($data->targets as $t)
    <li>
        {{ $t->user->name }}
        <span class="badge bg-{{ $t->status_color }}">
            {{ $t->status_label }}
        </span>
    </li>
@endforeach
</ul>

<p>{{ $data->catatan }}</p>

@if($data->surat->file_path)
<a href="{{ asset('storage/'.$data->surat->file_path) }}" target="_blank">
    Lihat Surat
</a>
@endif

</div>
</div>

@endsection