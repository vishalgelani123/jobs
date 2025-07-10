<?php

namespace App\Http\Requests\GeneralTermCondition;

use Illuminate\Foundation\Http\FormRequest;

class GeneralTermConditionStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'required',
            'description' => 'required',
        ];
    }
}
