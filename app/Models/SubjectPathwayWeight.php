<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectPathwayWeight extends Model
{
    use HasFactory;

    protected $fillable = [
        'learning_area_id',
        'pathway_cluster_id',
        'weight',
        'is_active',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function learningArea()
    {
        return $this->belongsTo(LearningArea::class);
    }

    public function pathwayCluster()
    {
        return $this->belongsTo(PathwayCluster::class);
    }
}
