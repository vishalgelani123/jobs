<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationDetailStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pan_account_no'    => 'required',
            'gst_status'        => 'required',
            'pf_no'             => $this->vendor_type == 'contractor' ? 'required' : '',
            'esic_no'           => $this->vendor_type == 'contractor' ? 'required' : '',
            'digital_signature' => 'required',
        ];
    }
}
