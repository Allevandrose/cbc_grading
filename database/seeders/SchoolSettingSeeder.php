<?php

namespace Database\Seeders;

use App\Models\SchoolSetting;
use Illuminate\Database\Seeder;

class SchoolSettingSeeder extends Seeder
{
    public function run(): void
    {
        SchoolSetting::create([
            'school_name' => 'PathCore Academy',
            'school_code' => 'PCA-2026',
            'registration_number' => 'MOE/PCA/001/2026',
            'address' => 'P.O. Box 12345-00100, Nairobi',
            'phone' => '+254 700 000 000',
            'email' => 'info@pathcore.ac.ke',
            'website' => 'www.pathcore.ac.ke',
            'logo_path' => 'logos/pathcore-logo.png',
            'principal_name' => 'Dr. John Mwangi',
            'principal_signature_path' => 'signatures/principal.png',
            'grading_scale' => json_encode([
                ['min' => 90, 'max' => 100, 'grade' => 'EE1', 'points' => 8],
                ['min' => 75, 'max' => 89, 'grade' => 'EE2', 'points' => 7],
                ['min' => 58, 'max' => 74, 'grade' => 'ME1', 'points' => 6],
                ['min' => 41, 'max' => 57, 'grade' => 'ME2', 'points' => 5],
                ['min' => 31, 'max' => 40, 'grade' => 'AE1', 'points' => 4],
                ['min' => 21, 'max' => 30, 'grade' => 'AE2', 'points' => 3],
                ['min' => 11, 'max' => 20, 'grade' => 'BE1', 'points' => 2],
                ['min' => 0, 'max' => 10, 'grade' => 'BE2', 'points' => 1],
            ]),
            'report_card_settings' => json_encode([
                'show_position' => true,
                'show_average' => true,
                'show_teacher_comments' => true,
                'show_principal_comments' => true,
                'header_color' => '#1e3a8a',
                'footer_text' => 'Empowering Future Leaders',
            ]),
            'is_active' => true,
        ]);

        $this->command->info('School settings seeded successfully!');
    }
}
