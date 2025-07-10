<?php

namespace App\Http\Requests\Inquiry;

use Illuminate\Foundation\Http\FormRequest;

class InquiryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'inquiry_date'                      => 'required',
            'end_date'                          => 'required',
            'vendor_type'                       => 'required',
            'name'                              => 'required|string|max:255',
            'general_term_condition_categories' => 'required',
            'remarks'                           => 'nullable|max:600',
        ];
    }
}
