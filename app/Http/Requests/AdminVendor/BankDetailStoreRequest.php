<?php

namespace App\Http\Requests\AdminVendor;

use Illuminate\Foundation\Http\FormRequest;

class BankDetailStoreRequest extends FormRequest
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
            'bank_branch_name_and_address' => 'required',
            'bank_branch_code'             => 'required',
            'bank_ifsc_code'               => 'required',
        ];
    }
}
