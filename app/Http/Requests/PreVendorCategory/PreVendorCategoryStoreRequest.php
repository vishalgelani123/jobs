<?php

namespace App\Http\Requests\PreVendorCategory;

use Illuminate\Foundation\Http\FormRequest;

class PreVendorCategoryStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:pre_vendor_categories',
        ];
    }
}
