<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category_id',
        'brand_id',
    ];

    protected function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    protected function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'product_features');
    }


}
