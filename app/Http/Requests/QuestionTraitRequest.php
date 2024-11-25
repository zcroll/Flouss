<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionTraitRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'question_id' => ['required', 'exists:questions'],
            'question_type_id' => ['required', 'integer'],
            'holland_trait' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
