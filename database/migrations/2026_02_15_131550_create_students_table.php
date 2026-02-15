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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('upi_number')->unique(); // Unique identifier
            $table->string('admission_number')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->string('birth_certificate_number')->nullable();
            $table->string('nationality')->default('Kenyan');

            // Contact Information
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();

            // School Information
            $table->integer('current_grade_level'); // 1-9, then 10-12
            $table->string('current_class')->nullable();
            $table->year('enrollment_year');
            $table->year('graduation_year')->nullable();

            // Parent/Guardian Information
            $table->string('parent_name');
            $table->string('parent_phone');
            $table->string('parent_email')->nullable();
            $table->string('parent_relationship')->default('Parent');

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_graduated')->default(false);

            $table->timestamps();

            // Indexes for fast queries
            $table->index('current_grade_level');
            $table->index('upi_number');
            $table->index('admission_number');
            $table->index(['first_name', 'last_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
