<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'required|string',
            'slug' => 'required|string|unique:brands,slug',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
