<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user->id),
            ],
            'phone' => 'required',
            'password' => 'sometimes',
            'date_of_birth' => 'required',
            'city' => 'sometimes',
            'state' => 'required',
            'country' => 'required',
            'is_active' => 'required|in:1,0',
            'tax_id' => 'required',
            'address' => 'required',
            'suite' => 'required',
            'zip_code' => 'required',
        ];
    }
}
