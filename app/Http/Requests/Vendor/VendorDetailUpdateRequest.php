<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VendorDetailUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vendor_type'             => 'required',
            'pre_vendor_sub_category' => 'required',
            'business_name'           => 'required',
            'mobile_number'           => [
                'required',
                'numeric',
                'digits:10',
                Rule::unique('vendors', 'phone_number_1')->ignore($this->vendor->id),
                Rule::unique('users', 'mobile')->ignore($this->vendor->invite_vendor_id, 'invite_vendor_id'),
            ],
            'email'                   => [
                'required',
                'email',
                Rule::unique('vendors', 'email')->ignore($this->vendor->id),
                Rule::unique('users', 'email')->ignore($this->vendor->invite_vendor_id, 'invite_vendor_id'),
            ],
        ];
    }
}
