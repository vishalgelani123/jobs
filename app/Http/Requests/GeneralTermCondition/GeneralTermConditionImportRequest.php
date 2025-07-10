<?php

namespace App\Http\Requests\GeneralTermCondition;

use Illuminate\Foundation\Http\FormRequest;

class GeneralTermConditionImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'term_condition_category' => 'required',
            'file'                    => 'required|file|mimetypes:text/plain,text/csv,application/csv',
        ];
    }
}
