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
        Schema::create('video_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->enum('video_type', ['upload', 'youtube'])->default('upload');
            $table->string('video_file')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('button_text')->default('Shop Now');
            $table->string('button_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('autoplay')->default(true);
            $table->boolean('muted')->default(true);
            $table->boolean('loop')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_settings');
    }
};
