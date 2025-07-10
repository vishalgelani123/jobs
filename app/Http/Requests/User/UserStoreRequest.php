<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'email'       => 'required|email|unique:users',
            'password'    => 'required|min:6',
            'mobile'      => 'required|numeric|digits:10',
            'type'        => 'required',
            'admin'       => 'required_if:type,drafter',
        ];
    }
}
