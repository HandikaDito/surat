@extends('layouts.app')

@section('content')
<div class="container">

    <div class="card">

        <div class="card-header d-flex justify-content-between">
            <span>Manajemen User</span>
        </div>

        <div class="card-body">

            {{-- SUCCESS --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
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

            {{-- FORM TAMBAH --}}
            <form method="POST" action="{{ route('user.store') }}" class="mb-4">
                @csrf

                <div class="row g-2">

                    <div class="col-md-2">
                        <input name="name" class="form-control" placeholder="Nama" required>
                    </div>

                    <div class="col-md-2">
                        <input name="email" class="form-control" placeholder="Email" required>
                    </div>

                    <div class="col-md-2">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>

                    <div class="col-md-2">
                        <input name="jabatan" class="form-control" placeholder="Jabatan">
                    </div>

                    <div class="col-md-2">
                        <select name="role_level" class="form-control" required>
                            <option value="">Role</option>
                            <option value="0">Admin</option>
                            <option value="1">Direktur Utama</option>
                            <option value="2">Direktur</option>
                            <option value="3">Kabag</option>
                            <option value="4">Kasubbag</option>
                            <option value="5">Staff</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">
                            <i class="fa fa-plus"></i> Tambah
                        </button>
                    </div>

                </div>
            </form>

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Jabatan</th>
                            <th width="160">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $u)
                        <tr>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>

                            <td>
                                <span class="badge bg-primary">
                                    {{ $u->role_name }}
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ $u->jabatan ?? '-' }}
                                </span>
                            </td>

                            <td>
                                <button class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#edit{{ $u->id }}">
                                    Edit
                                </button>

                                <form action="{{ route('user.destroy', $u->id) }}"
                                      method="POST"
                                      style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Hapus user?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- MODAL EDIT --}}
                        <div class="modal fade" id="edit{{ $u->id }}">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('user.update', $u->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5>Edit User</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">

                                            <input name="name"
                                                   value="{{ $u->name }}"
                                                   class="form-control mb-2"
                                                   required>

                                            <input name="email"
                                                   value="{{ $u->email }}"
                                                   class="form-control mb-2"
                                                   required>

                                            <input type="password"
                                                   name="password"
                                                   class="form-control mb-2"
                                                   placeholder="Password baru (opsional)">

                                            <input name="jabatan"
                                                   value="{{ $u->jabatan }}"
                                                   class="form-control mb-2"
                                                   placeholder="Jabatan">

                                            <select name="role_level" class="form-control">
                                                <option value="0" {{ $u->role_level == 0 ? 'selected' : '' }}>Admin</option>
                                                <option value="1" {{ $u->role_level == 1 ? 'selected' : '' }}>Dirut</option>
                                                <option value="2" {{ $u->role_level == 2 ? 'selected' : '' }}>Direktur</option>
                                                <option value="3" {{ $u->role_level == 3 ? 'selected' : '' }}>Kabag</option>
                                                <option value="4" {{ $u->role_level == 4 ? 'selected' : '' }}>Kasubbag</option>
                                                <option value="5" {{ $u->role_level == 5 ? 'selected' : '' }}>Staff</option>
                                            </select>

                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-primary">Update</button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada user</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            {{ $users->links() }}

        </div>

    </div>

</div>
@endsection