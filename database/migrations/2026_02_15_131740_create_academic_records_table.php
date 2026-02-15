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
        Schema::create('academic_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('learning_area_id')->constrained()->onDelete('cascade');
            $table->integer('grade_level'); // 1-9, then 10-12
            $table->integer('term'); // 1, 2, or 3
            $table->integer('year');
            $table->decimal('score', 5, 2); // Raw percentage 0-100
            $table->enum('assessment_type', ['KPSEA', 'SBA', 'KJSEA', 'CAT', 'EXAM'])->default('CAT');
            $table->json('metadata')->nullable(); // For additional data like grade descriptors
            $table->timestamps();

            // Composite indexes for fast queries
            $table->index(['student_id', 'grade_level', 'term', 'year']);
            $table->index(['student_id', 'assessment_type']);
            $table->index(['grade_level', 'term', 'year']);

            // Ensure no duplicate records
            $table->unique(['student_id', 'learning_area_id', 'grade_level', 'term', 'year'], 'unique_academic_record');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_records');
    }
};
