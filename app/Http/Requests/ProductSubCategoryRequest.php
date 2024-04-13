<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductSubCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required' , 'max:100' , 'string'],
            'slug' => ['required' , 'max:100', 'string'],
            'category_id' => ['required', 'exists:product_categories,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
