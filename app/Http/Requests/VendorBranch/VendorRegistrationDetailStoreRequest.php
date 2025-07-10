<?php

namespace App\Http\Requests\VendorBranch;

use Illuminate\Foundation\Http\FormRequest;

class VendorRegistrationDetailStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pan_account_no'    => 'required|size:10',
            'gst_no'            => 'required_if:gst_status,yes|size:15',
            //'pf_no'             => $this->vendor_type == 'contractor' ? 'required' : '',
            //'esic_no'           => $this->vendor_type == 'contractor' ? 'required' : '',
            //'digital_signature' => 'required',
        ];
    }
}
