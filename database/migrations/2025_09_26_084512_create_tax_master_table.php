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
        Schema::create('tax_master', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Sunglasses Tax", "Frames Tax"
            $table->string('slug')->unique(); // e.g., "sunglasses-tax", "frames-tax"
            $table->text('description')->nullable();

            // Tax configuration
            $table->decimal('tax_rate', 5, 2); // e.g., 18.00, 5.00
            $table->string('tax_type')->default('gst'); // gst, vat, etc.
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);

            // Category association
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('subcategory_id')->nullable();

            // HSN/SAC codes
            $table->string('hsn_code')->nullable();
            $table->string('sac_code')->nullable();

            // Tax components (for GST)
            $table->decimal('cgst_rate', 5, 2)->nullable(); // Central GST
            $table->decimal('sgst_rate', 5, 2)->nullable(); // State GST
            $table->decimal('igst_rate', 5, 2)->nullable(); // Integrated GST
            $table->decimal('cess_rate', 5, 2)->default(0); // Additional Cess

            // Company state configuration
            $table->string('company_state')->default('Gujarat'); // Your company's state
            $table->string('company_state_code')->default('24'); // State code for GST

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_master');
    }
};
