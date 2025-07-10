<?php

namespace App\Http\Requests\Branch;

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
            'pan_account_no'    => 'required|size:10',
            'gst_status'        => 'required',
            'gst_no'            => 'required_if:gst_status,yes|size:15',
            'attachment'        => 'required_if:gst_status,no',
            //'pf_no'             => $this->vendor_type == 'contractor' ? 'required' : '',
            //'esic_no'           => $this->vendor_type == 'contractor' ? 'required' : '',
        ];
    }
}
