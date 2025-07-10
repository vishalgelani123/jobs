<?php

namespace App\Http\Requests\PreVendorSubCategory;

use Illuminate\Foundation\Http\FormRequest;

class PreVendorSubCategoryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pre_vendor_category' => 'required|integer',
            'name' => 'required|string|max:255|unique:pre_vendor_sub_categories,name,' . $this->pre_vendor_sub_category->id,
        ];
    }
}
