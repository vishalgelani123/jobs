<?php

namespace App\Http\Requests\AdminVendor;

use Illuminate\Foundation\Http\FormRequest;

class VendorDetailStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'state'                        => 'required',
            'city'                         => 'required',
            'pin_code'                     => 'required',
            'phone_number_1'               => 'required|numeric|digits:10',
            'phone_number_2'               => 'nullable|numeric|digits:10',
            'email'                        => 'required|email',
            'name_of_contact_person'       => 'required',
            'contact_person_mobile_number' => 'required|numeric|digits:10',
            'contact_person_email'         => 'required|email',
        ];
    }
}
