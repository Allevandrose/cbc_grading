<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_pathway_weights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_area_id')->constrained('learning_areas')->onDelete('cascade');
            $table->foreignId('pathway_cluster_id')->constrained('pathway_clusters')->onDelete('cascade');
            $table->decimal('weight', 5, 2)->default(1.0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['learning_area_id', 'pathway_cluster_id'], 'unique_pathway_weight');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_pathway_weights');
    }
};
