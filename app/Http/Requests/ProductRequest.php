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
            'slug' => ['required', 'max:15'],
            'price' => ['required', 'numeric'],
            'sub_category_id' => ['required', 'exists:product_sub_categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
            'features' => ['nullable']
        ];

        if ($this->method() !== 'PATCH') {
            $rules['image'] = ['required', 'image'];
            $rules['slug'] = ['required', 'max:15', 'unique:products'];
        }

        return $rules;
    }

    public function authorize(): bool
    {
        return true;
    }
}
