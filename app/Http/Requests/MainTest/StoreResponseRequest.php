<?php

namespace App\Http\Requests\MainTest;

use Illuminate\Foundation\Http\FormRequest;

class StoreResponseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust this if you need specific authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'itemId' => 'required|integer',
            'type' => 'required|in:answered,skipped',
            'answer' => 'nullable|integer|min:1|max:5',
            'category' => 'required|string',
            'testStage' => 'required|in:holland_codes,basic_interests',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'itemId.required' => 'The item ID is required.',
            'itemId.integer' => 'The item ID must be an integer.',
            'type.required' => 'The response type is required.',
            'type.in' => 'The response type must be either "answered" or "skipped".',
            'answer.integer' => 'The answer must be an integer.',
            'answer.min' => 'The answer must be at least 1.',
            'answer.max' => 'The answer must not be greater than 5.',
            'category.required' => 'The category is required.',
            'category.string' => 'The category must be a string.',
            'testStage.required' => 'The test stage is required.',
            'testStage.in' => 'The test stage must be either "holland_codes" or "basic_interests".',
        ];
    }
}
