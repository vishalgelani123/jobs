<?php

namespace App\Http\Requests\InquiryContactDetail;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InquiryContactDetailUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $inquiry = $this->route('inquiry');

        return [
            'name'          => 'required|string|max:255',
            'email'         => [
                'required',
                'email',
                Rule::unique('inquiry_contact_details')
                    ->where(function ($query) use ($inquiry) {
                        return $query->where('inquiry_id', $inquiry->id);
                    })
                    ->ignore($this->inquiry_contact_detail_id),
            ],
            'mobile_number' => [
                'required',
                'numeric',
                Rule::unique('inquiry_contact_details')
                    ->where(function ($query) use ($inquiry) {
                        return $query->where('inquiry_id', $inquiry->id);
                    })
                    ->ignore($this->inquiry_contact_detail_id),
            ],
        ];
    }
}
