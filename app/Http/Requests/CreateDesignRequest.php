<?php

namespace App\Http\Requests;

use App\Models\DesignOption;
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

            'options' => ['required', 'array',"min:4"],
            'options.*.design_option_id' => ['required_with:options', 'exists:design_options,id',],
            'options.*.value' => ['nullable', 'string', 'max:255'],
        ];
    }
  public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $options = $this->input('options', []);

            if (!is_array($options) || count($options) < 4) {
                return; // 
            }

            $ids = collect($options)
                ->pluck('design_option_id')
                ->filter()
                ->map(fn($v) => (int)$v)
                ->values();

            if ($ids->count() !== $ids->unique()->count()) {
                $validator->errors()->add('options', 'لا يمكن اختيار نفس الـ option مرتين.');
                return;
            }

            $types = DesignOption::whereIn('id', $ids)->pluck('type')->unique()->values();

            $requiredTypes = ['collar', 'sleeve', 'pocket', 'fabric'];

            $missing = collect($requiredTypes)->diff($types);

            if ($missing->isNotEmpty()) {
                $validator->errors()->add(
                    'options',
                    'يجب اختيار خيار واحد على الأقل من كل نوع: ' . $missing->implode(', ')
                );
            }
        });
    }
}