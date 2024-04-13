<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSubCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'category_id'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class , 'category_id');
    }

}
