<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemsetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['nullable'],
            'title' => ['nullable'],
            'lead_in_text' => ['nullable'],
            'url' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
