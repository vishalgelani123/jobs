<?php

namespace App\Http\Requests\PreVendorDetail;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PreVendorDetailUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                    => 'required|string|max:255',
            'mobile'                  => [
                'required',
                'numeric',
                'digits:10',
                Rule::unique('pre_vendor_details', 'mobile')->ignore($this->pre_vendor_detail->id),
                Rule::unique('users', 'mobile')->ignore($this->pre_vendor_detail->id, 'invite_vendor_id'),
            ],
            'email'                   => [
                'required',
                Rule::unique('pre_vendor_details')->where(function ($query) {
                    return $query->where('vendor_type_id', $this->vendor_type);
                })->ignore($this->pre_vendor_detail->id),
            ],
            'pre_vendor_sub_category' => 'required',
            'state'                   => 'required',
            'city'                    => 'required',
            'vendor_type'             => 'required',
            'address'                 => 'nullable|max:600',
        ];
    }
}
