<?php

namespace App\Http\Requests\SmtpSetting;

use Illuminate\Foundation\Http\FormRequest;

class SmtpSettingStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mail_from_name'  => 'required|string|max:255',
            'mail_from_email' => 'required|email|max:255',
            'mail_username'   => 'required|email|max:255',
            'mail_password'   => 'required|max:255',
            'mail_port'       => 'required|integer',
            'mail_host'       => 'required|string|max:255',
            'mail_encryption' => 'required|string|max:255',
        ];
    }
}
