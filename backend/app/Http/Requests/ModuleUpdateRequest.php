<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModuleUpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string'],
            'vendor' => ['required', 'string'],
            'config' => ['required', 'json'],
            'active' => ['required'],
            'status' => ['required', 'string'],
            'last_synced_at' => ['required'],
            'store_id' => ['required', 'integer', 'exists:stores,id'],
        ];
    }
}
