<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pathway_clusters', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // STEM, SOCIAL, ARTS
            $table->string('name'); // Science, Technology, Engineering & Mathematics
            $table->text('description')->nullable();
            $table->json('career_opportunities')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pathway_clusters');
    }
};
