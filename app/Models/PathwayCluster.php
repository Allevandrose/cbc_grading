<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PathwayCluster extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'career_opportunities',
        'is_active',
    ];

    protected $casts = [
        'career_opportunities' => 'array',
        'is_active' => 'boolean',
    ];

    public function subjectWeights()
    {
        return $this->hasMany(SubjectPathwayWeight::class);
    }

    public function recommendations()
    {
        return $this->hasMany(PathwayRecommendation::class, 'recommended_pathway', 'code');
    }
}
