<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Term;
use Carbon\Carbon;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentYear = date('Y');

        $terms = [
            [
                'term_number' => 1,
                'name' => 'Term 1',
                'year' => $currentYear,
                'start_date' => Carbon::create($currentYear, 1, 15),
                'end_date' => Carbon::create($currentYear, 4, 15),
                'is_current' => $this->isCurrentTerm(1),
            ],
            [
                'term_number' => 2,
                'name' => 'Term 2',
                'year' => $currentYear,
                'start_date' => Carbon::create($currentYear, 5, 1),
                'end_date' => Carbon::create($currentYear, 8, 15),
                'is_current' => $this->isCurrentTerm(2),
            ],
            [
                'term_number' => 3,
                'name' => 'Term 3',
                'year' => $currentYear,
                'start_date' => Carbon::create($currentYear, 9, 1),
                'end_date' => Carbon::create($currentYear, 12, 15),
                'is_current' => $this->isCurrentTerm(3),
            ],
        ];

        foreach ($terms as $term) {
            Term::create($term);
        }

        $this->command->info('Terms seeded successfully!');
    }

    /**
     * Determine if a term is current based on current date.
     */
    private function isCurrentTerm($termNumber): bool
    {
        $month = date('n');

        return match ($termNumber) {
            1 => $month >= 1 && $month <= 4,
            2 => $month >= 5 && $month <= 8,
            3 => $month >= 9 && $month <= 12,
            default => false,
        };
    }
}
