<?php

namespace App\Http\Requests\VendorDocDetail;

use Illuminate\Foundation\Http\FormRequest;

class VendorDocDetailUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vendor_type'     => 'required',
            'vendor_doc_type' => 'required',
        ];
    }
}
