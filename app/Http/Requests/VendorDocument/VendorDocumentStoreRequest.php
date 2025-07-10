<?php

namespace App\Http\Requests\VendorDocument;

use Illuminate\Foundation\Http\FormRequest;

class VendorDocumentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'document' => 'required|array'
        ];
    }
}
