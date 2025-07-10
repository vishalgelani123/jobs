<?php

namespace App\Http\Requests\GeneralCharge;

use Illuminate\Foundation\Http\FormRequest;

class GeneralChargeStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:general_charges',
        ];
    }
}
