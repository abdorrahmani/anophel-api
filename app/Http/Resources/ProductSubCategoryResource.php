<?php

namespace App\Http\Resources;

use App\Models\ProductSubCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ProductSubCategory */
class ProductSubCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'parent' => $this->category,
        ];
    }
}
