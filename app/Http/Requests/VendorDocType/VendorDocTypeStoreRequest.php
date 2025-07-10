<?php

namespace App\Http\Requests\VendorDocType;

use Illuminate\Foundation\Http\FormRequest;

class VendorDocTypeStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255|unique:vendor_doc_types',
            'doc_type' => 'required'
        ];
    }
}
