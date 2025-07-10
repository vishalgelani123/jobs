<?php

namespace App\Http\Requests\InquirySendMail;

use Illuminate\Foundation\Http\FormRequest;

class InquirySendMailStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mail_description' => 'required|string',
            'send_mail_to_users' => 'required',
        ];
    }
}
