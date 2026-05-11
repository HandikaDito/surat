<?php

namespace App\Http\Requests\Disposition;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class StoreDispositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && !auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'surat_id' => 'required|exists:surat_masuk,id',

            // 🔥 MULTI TARGET
            'target_users' => 'required|array|min:1',
            'target_users.*' => [
                'exists:users,id',
                function ($attr, $value, $fail) {

                    $sender = auth()->user();
                    $target = User::find($value);

                    if (!$target) {
                        return;
                    }

                    // 🔥 pakai rule dari model (satu sumber kebenaran)
                    if (!$sender->canSendTo($target)) {
                        $fail('Tidak boleh mengirim ke user tersebut');
                    }
                }
            ],

            'catatan' => 'required|string|max:255',

            'deadline' => 'nullable|date|after_or_equal:today',
        ];
    }
}