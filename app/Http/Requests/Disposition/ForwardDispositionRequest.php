<?php

namespace App\Http\Requests\Disposition;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class ForwardDispositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'to_user_id' => [
                'required',
                'exists:users,id',
                function ($attr, $value, $fail) {
                    $user = User::find($value);
                    if ($user->role_level != auth()->user()->role_level + 1) {
                        $fail('Hanya boleh kirim ke level berikutnya');
                    }
                }
            ],

            'catatan' => 'required|string'
        ];
    }
}