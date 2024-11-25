<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OptionSetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['nullable'],
            'help_text' => ['nullable'],
            'type' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
