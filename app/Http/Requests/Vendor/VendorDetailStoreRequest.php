<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VendorDetailStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pre_vendor_sub_category'      => 'required',
            'state'                        => 'required',
            'city'                         => 'required',
            'phone_number_2'               => 'nullable|numeric|digits:10',
            'mobile_number'                => [
                'required',
                'numeric',
                'digits:10',
                Rule::unique('vendors', 'phone_number_1'),
                Rule::unique('users', 'mobile')
            ],
            'email'                        => [
                'required',
                'email',
                Rule::unique('vendors', 'email'),
                Rule::unique('users', 'email'),
            ],
            'name_of_contact_person'       => 'required',
            'contact_person_mobile_number' => 'required|numeric|digits:10',
            'contact_person_email'         => 'required|email',
            'address'                      => 'nullable|max:600',
        ];
    }
}
