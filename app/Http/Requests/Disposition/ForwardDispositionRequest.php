<?php

namespace App\Http\Requests\Disposition;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class ForwardDispositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
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

                    // 🔥 pakai rule pusat
                    if (!$sender->canSendTo($target)) {
                        $fail('Tidak boleh mengirim ke user tersebut');
                    }
                }
            ],

            'catatan' => 'nullable|string|max:255',
        ];
    }
}