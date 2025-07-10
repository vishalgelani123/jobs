<?php

namespace App\Http\Requests\VendorBranch;

use Illuminate\Foundation\Http\FormRequest;

class VendorBankDetailStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type_of_account'              => 'required',
            'bank_account_no'              => 'required',
            'bank_name'                    => 'required',
            'bank_branch_name_and_address' => 'required|max:600',
            'bank_branch_code'             => 'required',
            'bank_ifsc_code'               => 'required',
        ];
    }
}
