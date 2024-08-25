<?php

namespace App\Http\Requests\FoodFact;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'code' => 'integer|unique:food_facts,code',
            'status' => 'in:draft,trash,published',
            'imported_t' => 'date_format:Y-m-d H:i:s',
            'url' => 'url',
            'creator' => 'string|max:255',
            'created_t' => 'integer',
            'last_modified_t' => 'integer',
            'product_name' => 'string|max:255',
            'quantity' => 'string|max:255',
            'brands' => 'string|max:255',
            'categories' => 'string|max:255',
            'labels' => 'nullable|string|max:255',
            'cities' => 'nullable|string|max:255',
            'purchase_places' => 'nullable|string|max:255',
            'stores' => 'nullable|string|max:255',
            'ingredients_text' => 'string',
            'traces' => 'nullable|string|max:255',
            'serving_size' => 'string|max:255',
            'serving_quantity' => 'numeric',
            'nutriscore_score' => 'integer',
            'nutriscore_grade' => 'string|max:1',
            'main_category' => 'string|max:255',
            'image_url' => 'url',
        ];
    }
}
