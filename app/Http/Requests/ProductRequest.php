<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'slug' => ['required', 'max:15', 'unique:products'],
            'price' => ['required', 'numeric'],
            'category_id' => ['required', 'exists:product_categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
            'features' => ['nullable', 'array']
        ];

        if ($this->method() !== 'PATCH') {
            $rules['image'] = ['required', 'image'];
        }

        return $rules;
    }

    public function authorize(): bool
    {
        return true;
    }
}
