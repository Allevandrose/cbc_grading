<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_name',
        'school_code',
        'registration_number',
        'address',
        'phone',
        'email',
        'website',
        'logo_path',
        'principal_name',
        'principal_signature_path',
        'grading_scale',
        'report_card_settings',
        'is_active',
    ];

    protected $casts = [
        'grading_scale' => 'array',
        'report_card_settings' => 'array',
        'is_active' => 'boolean',
    ];

    public static function getSettings()
    {
        return self::where('is_active', true)->first();
    }
}
