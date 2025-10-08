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
        Schema::table('shapes', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // Generate slugs for existing shapes
        $shapes = \App\Models\Shape::all();
        foreach ($shapes as $shape) {
            $shape->slug = \Illuminate\Support\Str::slug($shape->name);
            $shape->save();
        }

        // Now make slug unique
        Schema::table('shapes', function (Blueprint $table) {
            $table->string('slug')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shapes', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
