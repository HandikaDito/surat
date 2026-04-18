<?php

namespace App\Http\Requests\Surat;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuratKeluarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nomor_surat'   => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tujuan'        => 'required|string|max:255', // 🔥 TAMBAH
            'perihal'       => 'required|string|max:255',
            'isi_surat'     => 'required|string',
        ];
    }
}