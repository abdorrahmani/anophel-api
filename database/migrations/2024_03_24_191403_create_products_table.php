<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {

        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_category_id')->nullable()->constrained('product_sub_categories');  // Optional sub-category
            $table->foreignId('brand_id')->constrained('brands');

            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->bigInteger('price');
            $table->string('image');
            $table->softDeletes();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
        Schema::dropIfExists('products');
    }
};
