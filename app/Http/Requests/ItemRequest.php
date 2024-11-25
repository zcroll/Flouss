<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'text' => ['nullable'],
            'help_text' => ['nullable'],
            'option_set_id' => ['nullable', 'exists:option_sets'],
            'is_completed' => ['nullable', 'boolean'],
            'career_id' => ['nullable', 'integer'],
            'degree_id' => ['nullable', 'exists:degrees'],
            'image_url' => ['nullable'],
            'image_colour' => ['nullable'],
            'itemset_id' => ['nullable', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
