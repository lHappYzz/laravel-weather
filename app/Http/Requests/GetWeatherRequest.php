<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetWeatherRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'city' => 'required|string|max:255',
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'city.required' => 'The city parameter is required.',
            'city.string' => 'The city parameter should be a string.',
            'city.max' => 'The city parameter should be less than 255 characters.',
        ];
    }
}
