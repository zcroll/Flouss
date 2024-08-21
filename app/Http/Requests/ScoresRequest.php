<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScoresRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'exists:users'],
            'scores' => ['nullable'],
            'jobs' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
