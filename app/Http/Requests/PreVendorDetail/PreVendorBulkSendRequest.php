<?php

namespace App\Http\Requests\PreVendorDetail;

use Illuminate\Foundation\Http\FormRequest;

class PreVendorBulkSendRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //'bulk_send' => 'required',
        ];
    }
}
