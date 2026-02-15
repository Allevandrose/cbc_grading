<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'term_number',
        'name',
        'year',
        'start_date',
        'end_date',
        'is_current',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the academic records for this term.
     */
    public function academicRecords()
    {
        return $this->hasMany(AcademicRecord::class, 'term', 'term_number')
            ->where('year', $this->year);
    }

    /**
     * Get the current active term.
     */
    public static function getCurrent()
    {
        return self::where('is_current', true)->first();
    }

    /**
     * Scope a query to only include current term.
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    /**
     * Scope a query to filter by year.
     */
    public function scopeYear($query, $year)
    {
        return $query->where('year', $year);
    }
}
