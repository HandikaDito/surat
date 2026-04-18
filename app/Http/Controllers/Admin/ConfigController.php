<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Config;

class ConfigController extends Controller
{
    /**
     * ================= INDEX =================
     * Tampilkan halaman settings
     */
    public function index()
    {
        // 🔥 ambil semua config jadi array: key => value
        $configs = Config::all()->pluck('value', 'key')->toArray();

        return view('admin.settings.index', compact('configs'));
    }

    /**
     * ================= UPDATE =================
     * Simpan perubahan settings
     */
    public function update(Request $request)
    {
        // 🔥 VALIDASI (whitelist field)
        $validated = $request->validate([
            // identitas sistem
            'app_name'      => 'required|string|max:100',
            'company_name'  => 'nullable|string|max:100',

            // kontak
            'email'         => 'nullable|email',
            'phone'         => 'nullable|string|max:20',

            // surat
            'surat_prefix'  => 'required|string|max:10',

            // upload
            'max_upload'    => 'required|numeric|min:1|max:20',
        ]);

        // 🔥 SIMPAN KE DATABASE
        foreach ($validated as $key => $value) {
            Config::set($key, $value);
        }

        return back()->with('success', 'Pengaturan berhasil disimpan');
    }
}