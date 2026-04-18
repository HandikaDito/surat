<?php

namespace App\Http\Requests\Surat;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuratMasukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nomor_surat'   => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'tanggal_masuk' => 'required|date',
            'pengirim'      => 'required|string|max:255',
            'perihal'       => 'required|string|max:255',
            'sifat'         => 'nullable|string|max:100',
            'file_pdf'      => 'nullable|mimes:pdf|max:2048',
        ];
    }
}