<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->string('isbn')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('shipping', 10, 2)->nullable();
            $table->integer('stock')->nullable();
            $table->json('attributes')->nullable();
            $table->json('rows')->nullable();
            $table->string('image')->nullable();
            $table->boolean('publish')->default(false);
            $table->string('state')->default('deliverable');
            $table->integer('sort')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
