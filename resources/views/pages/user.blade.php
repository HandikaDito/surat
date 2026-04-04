@extends('layout.main')

@push('script')
<script>
    $(document).on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $('#editModal form').attr('action', '{{ route('user.index') }}/' + id);
        $('#editModal input:hidden#id').val(id);
        $('#editModal input#name').val($(this).data('name'));
        $('#editModal input#phone').val($(this).data('phone'));
        $('#editModal input#email').val($(this).data('email'));

        // SET ROLE
        $('#editModal select#role').val($(this).data('role'));

        if ($(this).data('active') == 1) {
            $('#editModal input#is_active').prop('checked', true);
        } else {
            $('#editModal input#is_active').prop('checked', false);
        }
    });
</script>
@endpush

@section('content')
<x-breadcrumb :values="[__('menu.users')]">
    <button
        type="button"
        class="btn btn-primary btn-create"
        data-bs-toggle="modal"
        data-bs-target="#createModal">
        {{ __('menu.general.create') }}
    </button>
</x-breadcrumb>

<div class="card mb-5">
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
            <tr>
                <th>{{ __('model.user.name') }}</th>
                <th>{{ __('model.user.email') }}</th>
                <th>{{ __('model.user.phone') }}</th>
                <th>{{ __('model.user.is_active') }}</th>
                <th>{{ __('menu.general.action') }}</th>
            </tr>
            </thead>

            <tbody>
            @foreach($data as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>
                        <span class="badge bg-label-primary">
                            {{ __('model.user.' . ($user->is_active ? 'active' : 'nonactive')) }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-info btn-sm btn-edit"
                                data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}"
                                data-email="{{ $user->email }}"
                                data-phone="{{ $user->phone }}"
                                data-active="{{ $user->is_active }}"
                                data-role="{{ $user->role }}"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal">
                            {{ __('menu.general.edit') }}
                        </button>

                        <form action="{{ route('user.destroy', $user) }}" class="d-inline" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm btn-delete" type="button">
                                {{ __('menu.general.delete') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
    </div>
</div>

{!! $data->links() !!}

<!-- CREATE MODAL -->
<div class="modal fade" id="createModal" data-bs-backdrop="static">
    <div class="modal-dialog">
        <form class="modal-content" method="post" action="{{ route('user.store') }}">
            @csrf
            <div class="modal-header">
                <h5>{{ __('menu.general.create') }}</h5>
            </div>

            <div class="modal-body">
                <x-input-form name="name" label="Name"/>
                <x-input-form name="email" label="Email" type="email"/>
                <x-input-form name="phone" label="Phone"/>

                <!-- ROLE -->
                <div class="mb-3">
                    <label>Role</label>
                    <select name="role" class="form-control">
                        <option value="admin">Admin</option>
                        <option value="staff" selected>Staff</option>
                        <option value="pimpinan">Pimpinan</option>
                    </select>
                </div>

                <x-input-form name="password" label="Password" type="password"/>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModal" data-bs-backdrop="static">
    <div class="modal-dialog">
        <form class="modal-content" method="post">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5>{{ __('menu.general.edit') }}</h5>
            </div>

            <div class="modal-body">
                <input type="hidden" name="id" id="id">

                <x-input-form name="name" label="Name"/>
                <x-input-form name="email" label="Email" type="email"/>
                <x-input-form name="phone" label="Phone"/>

                <!-- ROLE -->
                <div class="mb-3">
                    <label>Role</label>
                    <select name="role" id="role" class="form-control">
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                        <option value="pimpinan">Pimpinan</option>
                    </select>
                </div>

                <div class="form-check">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input">
                    <label class="form-check-label">Active</label>
                </div>

                <div class="form-check">
                    <input type="checkbox" name="reset_password" class="form-check-input">
                    <label class="form-check-label">Reset Password</label>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
