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
        Schema::create('pathway_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->decimal('wes_percentage', 5, 2); // Weighted Entry Score
            $table->string('final_grade'); // EE1, EE2, ME1, etc.
            $table->integer('final_points'); // 1-8 points
            $table->json('cluster_scores'); // Store scores for each pathway
            $table->enum('recommended_pathway', ['STEM', 'SOCIAL', 'ARTS']);
            $table->json('subject_breakdown')->nullable(); // Detailed subject scores
            $table->integer('term');
            $table->year('year');
            $table->boolean('is_finalized')->default(false);
            $table->date('recommendation_date');
            $table->timestamps();

            // Indexes
            $table->index(['student_id', 'term', 'year']);
            $table->index('recommended_pathway');
            $table->index('is_finalized');

            // Ensure one recommendation per student per term/year
            $table->unique(['student_id', 'term', 'year'], 'unique_pathway_recommendation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pathway_recommendations');
    }
};
