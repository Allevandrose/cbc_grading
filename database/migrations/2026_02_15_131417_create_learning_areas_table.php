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
        Schema::create('learning_areas', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., 'MATH', 'ENG', 'SCI'
            $table->string('name'); // e.g., 'Mathematics', 'English'
            $table->text('description')->nullable();
            $table->enum('category', ['CORE', 'STEM', 'SOCIAL', 'ARTS'])->default('CORE');
            $table->json('applicable_grades'); // [1,2,3,4,5,6,7,8,9]
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_areas');
    }
};
