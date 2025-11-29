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
            'id' => 'sometimes',
            'from' => 'required|string',
            'date_received' => 'required|date',
            'sender_id' => 'required|exists:users,id',
            'tracking_id' => 'sometimes',
            'total_value' => 'required',
            'weight' => 'required',
            'files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'items' => 'sometimes',
            'status' => 'sometimes|in:1,2,3,4',
            // Item image validation
            'items.*.files.*' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'items.*.new_files.*' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'items.*.delete_file_ids' => 'nullable|array',
            'items.*.delete_file_ids.*' => 'exists:package_files,id',
            'note' => 'required_if:status,1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'items.*.files.*.image' => 'Each item file must be an image.',
            'items.*.files.*.mimes' => 'Item images must be in JPEG, PNG, or WebP format.',
            'items.*.files.*.max' => 'Each item image must not exceed 2MB.',
            'items.*.new_files.*.image' => 'Each new item file must be an image.',
            'items.*.new_files.*.mimes' => 'New item images must be in JPEG, PNG, or WebP format.',
            'items.*.new_files.*.max' => 'Each new item image must not exceed 2MB.',
        ];
    }
}
