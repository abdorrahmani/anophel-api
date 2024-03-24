<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('product_features', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('feature_id')->constrained('features');
            $table->string('value');
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('features');
        Schema::dropIfExists('product_features');
    }
};
