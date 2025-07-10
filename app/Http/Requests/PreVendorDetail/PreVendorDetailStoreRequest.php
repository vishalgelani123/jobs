<?php

namespace App\Http\Requests\PreVendorDetail;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PreVendorDetailStoreRequest extends FormRequest
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
                Rule::unique('pre_vendor_details', 'mobile'),
                Rule::unique('users', 'mobile')
            ],
            'email'                   => [
                'required',
                Rule::unique('pre_vendor_details')->where(function ($query) {
                    return $query->where('vendor_type_id', $this->vendor_type);
                }),
            ],
            'pre_vendor_sub_category' => 'required',
            'state'                   => 'required',
            'city'                    => 'required',
            'vendor_type'             => 'required',
            'address'                 => 'nullable|max:600',
        ];
    }
}
