<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clearances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('term_id')->constrained('terms');
            $table->foreignId('academic_year_id')->constrained('academic_years');
            $table->boolean('fee_cleared')->default(false);
            $table->boolean('library_cleared')->default(false);
            $table->boolean('lab_cleared')->default(false);
            $table->boolean('sports_cleared')->default(false);
            $table->boolean('discipline_cleared')->default(false);
            $table->boolean('overall_cleared')->default(false);
            $table->date('clearance_date')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('cleared_by')->nullable()->constrained('users');
            $table->timestamps();

            $table->unique(['student_id', 'term_id', 'academic_year_id'], 'unique_clearance');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clearances');
    }
};
