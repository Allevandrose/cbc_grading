<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        $currentYear = date('Y');
        $years = [
            [
                'name' => ($currentYear - 2) . ' Academic Year',
                'year' => $currentYear - 2,
                'start_date' => Carbon::create($currentYear - 2, 1, 15),
                'end_date' => Carbon::create($currentYear - 2, 12, 15),
                'is_current' => false,
            ],
            [
                'name' => ($currentYear - 1) . ' Academic Year',
                'year' => $currentYear - 1,
                'start_date' => Carbon::create($currentYear - 1, 1, 15),
                'end_date' => Carbon::create($currentYear - 1, 12, 15),
                'is_current' => false,
            ],
            [
                'name' => $currentYear . ' Academic Year',
                'year' => $currentYear,
                'start_date' => Carbon::create($currentYear, 1, 15),
                'end_date' => Carbon::create($currentYear, 12, 15),
                'is_current' => true,
            ],
            [
                'name' => ($currentYear + 1) . ' Academic Year',
                'year' => $currentYear + 1,
                'start_date' => Carbon::create($currentYear + 1, 1, 15),
                'end_date' => Carbon::create($currentYear + 1, 12, 15),
                'is_current' => false,
            ],
        ];

        foreach ($years as $year) {
            AcademicYear::create([
                'name' => $year['name'],
                'year' => $year['year'],
                'start_date' => $year['start_date'],
                'end_date' => $year['end_date'],
                'is_current' => $year['is_current'],
                'is_active' => true,
            ]);
        }

        $this->command->info('Academic years seeded successfully!');
    }
}
