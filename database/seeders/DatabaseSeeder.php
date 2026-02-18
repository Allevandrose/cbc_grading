<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Core system seeders (in order of dependencies)
            RoleSeeder::class,
            GradeLevelSeeder::class,
            AcademicYearSeeder::class,      // New
            TermSeeder::class,
            LearningAreaSeeder::class,
            PathwayClusterSeeder::class,     // New
            SubjectPathwayWeightSeeder::class, // New
            AssessmentTypeSeeder::class,      // New
            SchoolSettingSeeder::class,       // New
            
            // Data seeders
            StudentSeeder::class,
            ClassArmSeeder::class,           // New
            
            // Teacher assignments (optional - can be done via UI)
            // TeacherClassAssignmentSeeder::class,
            // SubjectTeacherAssignmentSeeder::class,
            
            // Accountant seeders (optional)
            // FeeStructureSeeder::class,
        ]);
    }
}