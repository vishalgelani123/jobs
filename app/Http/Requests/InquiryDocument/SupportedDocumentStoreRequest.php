<?php

namespace App\Http\Requests\InquiryDocument;

use Illuminate\Foundation\Http\FormRequest;

class SupportedDocumentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'images'   => 'required',
            'images.*' => 'required|mimes:jpg,jpeg,png,pdf,csv|max:20480',
        ];
    }

    public function messages(): array
    {
        return [
            'images'       => 'Supported document field is required.',
            'images.*.mimes' => 'Each file must be a valid format: JPG, JPEG, PNG, PDF, CSV.',
            'images.*.max'   => 'Each file must not exceed 20MB.',
        ];
    }
}
