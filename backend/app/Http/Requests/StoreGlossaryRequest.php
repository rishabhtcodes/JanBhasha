<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGlossaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'source_term'    => ['required', 'string', 'max:255'],
            'target_term'    => ['required', 'string', 'max:255'],
            'case_sensitive' => ['boolean'],
            'notes'          => ['nullable', 'string', 'max:1000'],
        ];
    }
}
