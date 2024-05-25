<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feature extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
    ];


    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_features')->withPivot('value');
    }

}
