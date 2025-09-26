<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_methods_master', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Razorpay, PayU, COD
            $table->string('slug')->unique(); // razorpay, payu, cod
            $table->string('display_name'); // Razorpay, PayU, Cash on Delivery
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // FontAwesome icon class
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);

            // Payment gateway specific fields
            $table->json('credentials')->nullable(); // Store API keys, secrets, etc.
            $table->json('settings')->nullable(); // Additional settings like test mode, etc.

            // Razorpay specific
            $table->string('razorpay_key_id')->nullable();
            $table->string('razorpay_key_secret')->nullable();
            $table->boolean('razorpay_test_mode')->default(true);

            // PayU specific
            $table->string('payu_merchant_key')->nullable();
            $table->string('payu_merchant_salt')->nullable();
            $table->string('payu_authorization_header')->nullable();
            $table->boolean('payu_test_mode')->default(true);

            // COD specific
            $table->decimal('cod_minimum_amount', 10, 2)->nullable();
            $table->decimal('cod_maximum_amount', 10, 2)->nullable();
            $table->text('cod_instructions')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods_master');
    }
};
