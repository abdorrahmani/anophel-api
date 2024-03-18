<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    public function rules(): array
    {
        if ($this->method() === 'PATCH') {
            return [
                'title' => ['string', "max:255", 'required'],
                'slug' => ['string', 'max:255', 'required'],
                'poster' => ['mimes:jpg,jpeg,png,bmp'],
                'body' => ['required'],
                'category_id' => ['string', 'required'],
            ];
        }

        return [
            'title' => ['string', 'max:255', 'required'],
            'slug' => ['string', 'max:255', 'required'],
            'poster' => ['mimes:jpg,jpeg,png,bmp', 'required'],
            'body' => ['required'],
            'category_id' => ['string', 'required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
