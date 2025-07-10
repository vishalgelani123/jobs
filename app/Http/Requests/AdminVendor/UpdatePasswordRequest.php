<?php

namespace App\Http\Requests\AdminVendor;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'password'         => 'required|min:6|max:12',
            'confirm_password' => 'required|same:password',
        ];
    }
}
