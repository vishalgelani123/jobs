<?php

namespace App\Http\Requests\GeneralTermConditionCategory;

use Illuminate\Foundation\Http\FormRequest;

class GeneralTermConditionCategoryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:general_term_condition_categories,name,' . $this->general_term_condition_category->id,
        ];
    }
}
