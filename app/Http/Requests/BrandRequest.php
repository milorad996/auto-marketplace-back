<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
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
            'brand_name' => 'required|string|max:255|unique:brands,brand_name',
        ];
    }

    public function messages(): array
    {
        return [
            'brand_name.required' => 'The brand name is required.',
            'brand_name.string' => 'The brand name must be a string.',
            'brand_name.max' => 'The brand name cannot exceed 255 characters.',
            'brand_name.unique' => 'This brand name already exists.',
        ];
    }
}
