<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTranslationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'source_text'  => ['required', 'string', 'min:1', 'max:50000'],
            'source_label' => ['nullable', 'string', 'max:255'],
            'source_lang'  => ['nullable', 'string', 'size:2'],
            'target_lang'  => ['nullable', 'string', 'size:2'],
        ];
    }

    public function messages(): array
    {
        return [
            'source_text.required' => 'Please provide the text to translate.',
            'source_text.max'      => 'Text must not exceed 50,000 characters.',
        ];
    }
}
