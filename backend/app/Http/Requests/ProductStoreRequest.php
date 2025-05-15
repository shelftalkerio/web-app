<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
            'sku' => ['required', 'string'],
            'price' => ['required', 'numeric', 'between:-999999.99,999999.99'],
            'stock' => ['required', 'integer'],
            'synced_at' => ['required'],
            'description' => ['nullable', 'string'],
            'store_id' => ['required', 'integer', 'exists:stores,id'],
        ];
    }
}
