<?php

namespace App\Http\Requests\Disposition;

use Illuminate\Foundation\Http\FormRequest;

class DoneDispositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            // 🔥 catatan boleh kosong (lebih fleksibel)
            'catatan' => 'nullable|string|max:500',

            // 🔥 FILE WAJIB
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,mp4,mov|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Laporan wajib diupload',
            'file.mimes'    => 'Format file harus PDF, gambar, atau video',
            'file.max'      => 'Ukuran file maksimal 10MB',
        ];
    }
}