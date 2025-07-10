<?php

namespace App\Http\Requests\InquiryGeneralCharge;

use Illuminate\Foundation\Http\FormRequest;

class InquiryGeneralChargeStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'general_charges_name' => 'required',
        ];
    }
}
