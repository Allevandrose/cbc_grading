<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('term_id')->constrained('terms');
            $table->foreignId('academic_year_id')->constrained('academic_years');
            $table->string('report_number')->unique();
            $table->json('subject_grades');
            $table->decimal('total_marks', 8, 2)->nullable();
            $table->decimal('average', 5, 2)->nullable();
            $table->string('overall_grade')->nullable();
            $table->integer('position')->nullable();
            $table->text('teacher_comments')->nullable();
            $table->text('principal_comments')->nullable();
            $table->string('file_path')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->unique(['student_id', 'term_id', 'academic_year_id'], 'unique_report_card');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_cards');
    }
};
