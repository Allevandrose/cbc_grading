<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_arms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_level_id')->constrained('grade_levels')->onDelete('cascade');
            $table->string('name'); // A, B, C, etc.
            $table->string('code')->unique(); // 7A, 8B, etc.
            $table->integer('capacity')->default(45);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['grade_level_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_arms');
    }
};
