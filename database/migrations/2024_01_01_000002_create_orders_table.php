<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('salutation')->nullable();
            $table->string('company')->nullable();
            $table->string('name')->nullable();
            $table->string('firstname')->nullable();
            $table->string('street');
            $table->string('street_number')->nullable();
            $table->string('zip');
            $table->string('city');
            $table->string('country');
            $table->string('email');
            $table->boolean('use_invoice_address')->default(false);
            $table->string('shipping_company')->nullable();
            $table->string('shipping_name')->nullable();
            $table->string('shipping_firstname')->nullable();
            $table->string('shipping_street')->nullable();
            $table->string('shipping_street_number')->nullable();
            $table->string('shipping_zip')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('payment_method');
            $table->decimal('total', 10, 2)->nullable();
            $table->timestamp('payed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
