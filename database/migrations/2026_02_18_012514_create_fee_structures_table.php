<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_level_id')->constrained('grade_levels');
            $table->foreignId('term_id')->constrained('terms');
            $table->foreignId('academic_year_id')->constrained('academic_years');
            $table->decimal('tuition_fee', 10, 2);
            $table->decimal('activity_fee', 10, 2)->default(0);
            $table->decimal('transport_fee', 10, 2)->default(0);
            $table->decimal('boarding_fee', 10, 2)->default(0);
            $table->decimal('uniform_fee', 10, 2)->default(0);
            $table->decimal('other_fees', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->date('due_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['grade_level_id', 'term_id', 'academic_year_id'], 'unique_fee_structure');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
};
