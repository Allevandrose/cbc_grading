<?php

namespace Database\Seeders;

use App\Models\ClassArm;
use App\Models\GradeLevel;
use Illuminate\Database\Seeder;

class ClassArmSeeder extends Seeder
{
    public function run(): void
    {
        $grades = GradeLevel::whereBetween('grade', [1, 9])->get();
        $classLetters = ['A', 'B', 'C', 'D'];

        foreach ($grades as $grade) {
            foreach ($classLetters as $letter) {
                ClassArm::create([
                    'grade_level_id' => $grade->id,
                    'name' => $letter,
                    'code' => $grade->grade . $letter,
                    'capacity' => 45,
                    'is_active' => true,
                ]);
            }
        }

        // Add extra classes for upper grades (7-9 may have more streams)
        $upperGrades = GradeLevel::whereBetween('grade', [7, 9])->get();
        $extraLetters = ['E', 'F'];

        foreach ($upperGrades as $grade) {
            foreach ($extraLetters as $letter) {
                ClassArm::create([
                    'grade_level_id' => $grade->id,
                    'name' => $letter,
                    'code' => $grade->grade . $letter,
                    'capacity' => 45,
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('Class arms seeded successfully!');
    }
}
