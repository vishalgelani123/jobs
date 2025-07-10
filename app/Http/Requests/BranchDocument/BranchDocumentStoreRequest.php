<?php

namespace App\Http\Requests\BranchDocument;

use Illuminate\Foundation\Http\FormRequest;

class BranchDocumentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'document' => 'required|array',
            'document.*' => 'required|mimes:pdf|max:20480',
        ];
    }

    public function messages(): array
    {
        return [
            'document.*.mimes' => 'Each document must be a PDF file.',
            'document.*.max' => 'Each document must not exceed 10MB in size.',
        ];
    }
}
