<?php

namespace App\Http\Requests;

use App\Enum\DesignOptionEnumType;
use App\Enum\PaymentEnumOrder;
use App\Models\Design;
use App\Models\DesignOption;
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

        $requiredTypes = [
            DesignOptionEnumType::COLLAR,
            DesignOptionEnumType::SLEEVE,
            DesignOptionEnumType::POCKET,
            DesignOptionEnumType::FABRIC,
        ];

        foreach ($items as $i => $item) {
            $designId = $item['design_id'] ?? null;
            $optionIds = $item['design_option_ids'] ?? [];

            if (!$designId) continue;

            $optionIds = array_values(array_unique($optionIds));
            if (count($optionIds) !== 4) {
                $validator->errors()->add("items.$i.design_option_ids", "لازم تختار 4 خيارات بدون تكرار");
                continue;
            }

            $validCount = DB::table('design_option_selections')
                ->where('design_id', $designId)
                ->whereIn('design_option_id', $optionIds)
                ->distinct()
                ->count('design_option_id');

            if ($validCount !== 4) {
                $validator->errors()->add("items.$i.design_option_ids", "في خيارات مو تابعة لهالتصميم");
                continue;
            }

            $types = DesignOption::whereIn('id', $optionIds)->pluck('type')->all();

            // لازم يكون عندك 4 types
            if (count($types) !== 4) {
                $validator->errors()->add("items.$i.design_option_ids", "خيارات غير صحيحة");
                continue;
            }

            // لازم يكونوا كلهم مختلفين
            if (count(array_unique($types)) !== 4) {
                $validator->errors()->add("items.$i.design_option_ids", "لازم تختار خيار واحد فقط من كل نوع (collar/sleeve/pocket/fabric)");
                continue;
            }

            // لازم يغطي الأنواع المطلوبة
            foreach ($requiredTypes as $t) {
                if (!in_array($t, $types, true)) {
                    $validator->errors()->add("items.$i.design_option_ids", "لازم يكون في خيار من نوع: {$t}");
                    break;
                }
            }
        }
    });
}}
