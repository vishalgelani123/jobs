<?php

namespace App\Http\Requests\VendorDocDetail;

use Illuminate\Foundation\Http\FormRequest;

class VendorDocDetailStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
