<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // CAT, EXAM, KPSEA, SBA, KJSEA
            $table->string('name'); // Continuous Assessment Test, End Term Exam, etc.
            $table->text('description')->nullable();
            $table->decimal('weight', 5, 2)->default(1.0);
            $table->json('applicable_grades')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_types');
    }
};
