<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OptionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'text' => ['nullable'],
            'help_text' => ['nullable'],
            'value' => ['nullable', 'numeric'],
            'reverse_coded_value' => ['nullable', 'numeric'],
            'option_set_id' => ['nullable', 'exists:option_sets'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
