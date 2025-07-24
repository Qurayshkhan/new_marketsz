<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
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
            'from' => 'required|string',
            'date_received' => 'required|date',
            'sender_id' => 'required|exists:users,id',
            'tracking_id' => 'required|string',
            'total_value' => 'required',
            'weight' => 'required',
            'files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'items' => 'sometimes',
        ];
    }
}
