<?php

namespace App\Http\Requests\VendorBranch;

use Illuminate\Foundation\Http\FormRequest;

class VendorBranchStoreRequest extends FormRequest
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
            'address'                      => 'nullable|max:600',
            'mobile_number'                => 'required|numeric|digits:10',
            'phone_number_2'               => 'nullable|numeric|digits:10',
            'email'                        => 'required|email',
            'name_of_contact_person'       => 'required',
            'contact_person_mobile_number' => 'required|numeric|digits:10',
            'contact_person_email'         => 'required|email',
        ];
    }
}
