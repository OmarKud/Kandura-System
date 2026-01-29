<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDesignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],


            'description' => ['nullable', 'string'],
 'measurement_ids' => 'required|array|min:1',
        'measurement_ids.*' => 'integer|exists:measurements,id',
            'price' => ['required', 'numeric', 'min:0'],

            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'image', 'max:2048'],

            'options' => ['required', 'array',"min:5"],
            'options.*.design_option_id' => ['required_with:options', 'exists:design_options,id',],
            'options.*.value' => ['nullable', 'string', 'max:255'],
        ];
    }
}
