<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PricingPlanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'price_subtext' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
            'badge_text' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'features' => 'required|array|min:1',
            'features.*.name' => 'required|string|max:255',
            'features.*.is_highlighted' => 'nullable|boolean',
        ];
    }
}
