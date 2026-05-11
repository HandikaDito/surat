@extends('layouts.app')

@section('content')

<div class="row">

    {{-- 🔥 FULL WIDTH DI HP --}}
    <div class="col-12 col-md-6 mx-auto">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    <i class="fa fa-user"></i> Profil Saya
                </span>
            </div>

            <div class="card-body">

                {{-- SUCCESS --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

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

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    {{-- NAMA --}}
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text"
                               name="name"
                               value="{{ auth()->user()->name }}"
                               class="form-control"
                               required>
                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text"
                               value="{{ auth()->user()->email }}"
                               class="form-control"
                               disabled>
                    </div>

                    {{-- ROLE --}}
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <input type="text"
                               value="{{ auth()->user()->role_name }}"
                               class="form-control"
                               disabled>
                    </div>

                    <hr>

                    {{-- PASSWORD --}}
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password"
                               name="password"
                               class="form-control"
                               placeholder="Kosongkan jika tidak ingin mengganti">
                    </div>

                    {{-- KONFIRMASI --}}
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password"
                               name="password_confirmation"
                               class="form-control">
                    </div>

                    {{-- 🔥 BUTTON RESPONSIVE --}}
                    <div class="d-grid d-md-flex justify-content-md-end">
                        <button class="btn btn-primary w-100 w-md-auto">
                            <i class="fa fa-save"></i> Update Profil
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection