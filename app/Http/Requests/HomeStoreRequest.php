<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HomeStoreRequest extends FormRequest
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
            'zip_code' => ['required', 'string'],
            'purpose' => ['required', Rule::in(['rent', 'sell'])],
            'title' => ['required', 'string', 'min:3', 'max:250'],
            'type_id' => ['required', 'exists:home_types,id'],
            'price' => ['required', 'integer'],
            'bedrooms' => ['required', 'string', Rule::in(['1', '2', '3', '4', '+4'])],
            'bathrooms' => ['required', 'string', Rule::in(['1', '2', '3', '+3'])],
            'condition_id' => ['required', 'exists:home_conditions,id'],
            'm_two' => ['nullable', 'integer'],
            'price_m_two' => ['nullable', 'integer'],
        ];
    }
}
