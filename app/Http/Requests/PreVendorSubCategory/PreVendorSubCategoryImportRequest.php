<?php

namespace App\Http\Requests\PreVendorSubCategory;

use Illuminate\Foundation\Http\FormRequest;

class PreVendorSubCategoryImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pre_vendor_category' => 'required',
            'file'                => 'required|file'
        ];
    }
}
