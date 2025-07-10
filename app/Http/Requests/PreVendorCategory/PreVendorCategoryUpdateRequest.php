<?php

namespace App\Http\Requests\PreVendorCategory;

use Illuminate\Foundation\Http\FormRequest;

class PreVendorCategoryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:pre_vendor_categories,name,' . $this->pre_vendor_category->id,
        ];
    }
}
