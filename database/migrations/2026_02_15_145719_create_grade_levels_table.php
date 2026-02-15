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
        Schema::create('grade_levels', function (Blueprint $table) {
            $table->id();
            $table->integer('grade')->unique(); // 1,2,3,4,5,6,7,8,9,10,11,12
            $table->string('name'); // e.g., "Grade 1", "Grade 2", etc.
            $table->string('stage')->nullable(); // "Lower Primary", "Upper Primary", "Junior Secondary", "Senior Secondary"
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_levels');
    }
};
