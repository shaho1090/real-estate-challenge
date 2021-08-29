<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['bail','required','string','min:2','max:150'],
            'family' => ['bail','required','string','min:2','max:150'],
            'phone' => ['bail','required','string','min:8','max:20'],
            'email' => ['bail','required', 'email', 'unique:users'],
            'password' => ['bail','required','min:6','max:50'],
            'address' => ['bail', 'required', 'string', 'min:10','max:250']
        ];
    }
}
