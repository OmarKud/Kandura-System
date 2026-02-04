<?php

namespace App\Http\Requests;

use App\Enum\PaymentEnumOrder;
use App\Models\Design;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CreateOrderRequest extends FormRequest
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
            'address_id' => ['required', 'integer', 'exists:addresses,id'],
            "payment_method" => ["required", Rule::in([PaymentEnumOrder::STRIPE, PaymentEnumOrder::WALLET, PaymentEnumOrder::DELIVERY]),]
            ,
            "notes" => ["nullable", "string"],
            'coupon_code' => ['nullable','string','max:50'],
 'items' => ['required', 'array', 'min:1'],
        'items.*.design_id' => ['required', 'exists:designs,id'],
        'items.*.design_option_ids' => ['required', 'array',"min:4","max:4"],
        'items.*.design_option_ids.*' => ["required",'integer', 'exists:design_options,id'],
'items.*.measurement_id' => ['required','exists:measurements,id'],
        ];
    }
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
        $items = $this->input('items', []);

        foreach ($items as $index => $item) {
            $designId = $item['design_id'] ?? null;
            $measurementId = $item['measurement_id'] ?? null;

            if ($designId && $measurementId) {
                $ok = Design::where('id', $designId)
                    ->whereHas('measurements', fn($q) => $q->where('measurements.id', $measurementId))
                    ->exists();

                if (!$ok) {
                    $validator->errors()->add("items.$index.measurement_id", "هذا المقاس غير متاح لهذا التصميم");
                }
            }
        }
    });
        $validator->after(function ($validator) {
            $items = $this->input('items', []);

            foreach ($items as $i => $item) {
                $designId = $item['design_id'] ?? null;
                $optionIds = $item['design_option_ids'] ?? [];

                if (!$designId || empty($optionIds)) {
                    continue;
                }

                $validCount = DB::table('design_option_selections')
                    ->where('design_id', $designId)
                    ->whereIn('design_option_id', $optionIds)
                    ->distinct()
                    ->count('design_option_id');

                if ($validCount !== count(array_unique($optionIds))) {
                    $validator->errors()->add(
                        "items.$i.design_option_ids",
                       " this option is not valid in this design"
                    );
                }
            }
        });
    }

}
