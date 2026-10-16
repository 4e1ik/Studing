<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'chat_id' => 'nullable|integer',
            'receiver_id' => 'required|integer|exists:users,id',
            'message' => 'nullable|string',
            'old_message_id' => 'nullable|integer',
            'files' => 'nullable|array',
            'files.*' => 'nullable|file|mimes:jpeg,jpg,png,gif,doc,docx,pdf',
        ];
    }
}
