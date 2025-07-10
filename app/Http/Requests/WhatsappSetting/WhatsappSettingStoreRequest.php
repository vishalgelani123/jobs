<?php

namespace App\Http\Requests\WhatsappSetting;

use Illuminate\Foundation\Http\FormRequest;

class WhatsappSettingStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'whatsapp_from_name' => 'required|string|max:255',
            'whatsapp_number'    => 'required',

        ];
    }
}
