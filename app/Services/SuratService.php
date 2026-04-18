<?php

namespace App\Services;

use App\Models\SuratMasuk;
use App\Models\SuratKeluar;

class SuratService
{
    // 📥 Surat Masuk
    public function storeMasuk(array $data)
    {
        return SuratMasuk::create($data);
    }

    // 📤 Surat Keluar
    public function storeKeluar(array $data)
    {
        return SuratKeluar::create([
            ...$data,
            'created_by' => auth()->id(),
            'status' => 'draft'
        ]);
    }

    public function getAllKeluar()
    {
        return SuratKeluar::with('creator') // 🔥 FIX
            ->latest()
            ->get();
    }
}