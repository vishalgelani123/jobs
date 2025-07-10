<?php

namespace App\Http\Requests\PreVendorFollowupDetail;

use Illuminate\Foundation\Http\FormRequest;

class PreVendorFollowupDetailUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'               => 'required',
            'date'               => 'required',
            'next_followup_date' => 'required',
            'remark'             => 'nullable|max:600',
        ];
    }
}
