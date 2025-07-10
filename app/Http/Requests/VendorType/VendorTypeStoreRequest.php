<?php

namespace App\Http\Requests\VendorType;

use Illuminate\Foundation\Http\FormRequest;

class VendorTypeStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:vendor_types',
        ];
    }
}
