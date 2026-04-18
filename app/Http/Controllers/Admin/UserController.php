<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ================= LIST USER =================
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    // ================= STORE USER =================
    public function store(StoreUserRequest $request)
    {
        User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role_level' => $request->role_level,
            'jabatan'    => $request->jabatan, // 🔥 TAMBAH
        ]);

        return back()->with('success', 'User berhasil ditambahkan');
    }

    // ================= UPDATE USER =================
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = [
            'name'       => $request->name,
            'email'      => $request->email,
            'role_level' => $request->role_level,
            'jabatan'    => $request->jabatan, // 🔥 TAMBAH
        ];

        // 🔥 password opsional
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'User berhasil diupdate');
    }

    // ================= DELETE USER =================
    public function destroy(User $user)
    {
        // 🔥 tidak boleh hapus diri sendiri
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
}