<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
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
            'price' => 'required|numeric|min:0',
            'manufacture_year' => 'required|integer|min:1900|max:' . date('Y'),
            'mileage' => 'required|integer|min:0',
            'body_type' => 'required|string|max:255',
            'fuel_type' => 'required|string|max:255',
            'door_count' => 'required|integer|min:1|max:10',
            'description' => 'nullable|string|max:1000',
            // 'images.*' => 'nullable|mimes:jpg,jpeg,png,webp|max:5120|url',
        ];
    }

    public function messages(): array
    {
        return [
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a numeric value.',
            'price.min' => 'The price must be at least :min.',
            'manufacture_year.required' => 'The manufacture year is required.',
            'manufacture_year.integer' => 'The manufacture year must be a valid integer.',
            'manufacture_year.min' => 'The manufacture year must be at least :min.',
            'manufacture_year.max' => 'The manufacture year cannot exceed the current year.',
            'mileage.required' => 'The mileage is required.',
            'mileage.integer' => 'The mileage must be a valid integer.',
            'mileage.min' => 'The mileage must be at least :min.',
            'body_type.required' => 'The body type is required.',
            'body_type.string' => 'The body type must be a valid string.',
            'body_type.max' => 'The body type must not exceed :max characters.',
            'fuel_type.required' => 'The fuel type is required.',
            'fuel_type.string' => 'The fuel type must be a valid string.',
            'fuel_type.max' => 'The fuel type must not exceed :max characters.',
            'door_count.required' => 'The door count is required.',
            'door_count.integer' => 'The door count must be a valid integer.',
            'door_count.min' => 'The door count must be at least :min.',
            'door_count.max' => 'The door count must not exceed :max.',
            'description.string' => 'The description must be a valid string.',
            'description.max' => 'The description must not exceed :max characters.',
            'images.*.image' => 'Each file must be an image.',
            'images.*.mimes' => 'Only JPG, JPEG, PNG, and WEBP formats are allowed.',
            'images.*.max' => 'Each image must not exceed 5MB in size.',
        ];
    }
}
