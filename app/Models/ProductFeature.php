<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{
    protected $fillable = ['value','feature_id' , 'product_id'];
}
