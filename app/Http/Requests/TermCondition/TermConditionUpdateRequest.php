<?php

namespace App\Http\Requests\TermCondition;

use Illuminate\Foundation\Http\FormRequest;

class TermConditionUpdateRequest extends FormRequest
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
