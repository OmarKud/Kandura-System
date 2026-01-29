<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDesignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],

            'description' => ['sometimes', 'nullable', 'string'],

            'measurement_ids' => 'nullable|array|min:1',
        'measurement_ids.*' => 'integer|exists:measurements,id',

            'price' => ['sometimes', 'numeric', 'min:0'],

            'images' => ['sometimes', 'array'],
            'images.*' => ['required_with:images', 'image', 'max:2048'],

            'options' => ['sometimes', 'nullable', 'array'],
            'options.*.design_option_id' => ['required_with:options', 'exists:design_options,id'],
            'options.*.value' => ['nullable', 'string', 'max:255'],
        ];
    }
}
