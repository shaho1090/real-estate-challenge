<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HomeUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'zip_code' => ['sometimes', 'string'],
            'purpose' => ['sometimes', Rule::in(['rent', 'sell'])],
            'title' => ['sometimes', 'string', 'min:3', 'max:250'],
            'type_id' => ['sometimes', 'exists:home_types,id'],
            'price' => ['sometimes', 'integer'],
            'bedrooms' => ['sometimes', 'string', Rule::in(['1', '2', '3', '4', '+4'])],
            'bathrooms' => ['sometimes', 'string', Rule::in(['1', '2', '3', '+3'])],
            'condition_id' => ['sometimes', 'exists:home_conditions,id'],
            'm_two' => ['nullable', 'integer'],
            'price_m_two' => ['nullable', 'integer'],
        ];
    }
}
