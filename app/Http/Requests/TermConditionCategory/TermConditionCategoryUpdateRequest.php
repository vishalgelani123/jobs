<?php

namespace App\Http\Requests\TermConditionCategory;

use Illuminate\Foundation\Http\FormRequest;

class TermConditionCategoryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vendor_type' => 'required',
            'name'        => 'required|string|max:255|unique:term_condition_categories,name,' . $this->term_condition_category->id,
        ];
    }
}
