<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\GradeLevel;
use App\Models\ClassArm;
use Carbon\Carbon;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kenyan first names
        $firstNames = [
            'John',
            'Jane',
            'Peter',
            'Mary',
            'James',
            'Elizabeth',
            'Joseph',
            'Sarah',
            'David',
            'Grace',
            'Daniel',
            'Esther',
            'Paul',
            'Ruth',
            'Samuel',
            'Hannah',
            'Alex',
            'Faith',
            'Brian',
            'Mercy',
            'Kevin',
            'Joy',
            'Eric',
            'Agnes',
            'Timothy',
            'Rose',
            'Patrick',
            'Lucy',
            'Francis',
            'Alice',
            'Charles',
            'Ann',
            'George',
            'Catherine',
            'Kennedy',
            'Monica',
            'Simon',
            'Florence',
            'Vincent',
            'Boniface',
            'Rebecca',
            'Moses',
            'Martha',
            'Jacob',
            'Veronica',
            'Philip',
            'Dorothy',
            'Julius',
            'Margaret',
            'Michael',
            'Cynthia',
            'Stephen',
            'Priscilla',
            'Martin',
            'Caroline'
        ];

        // Kenyan last names
        $lastNames = [
            'Omondi',
            'Wanjiku',
            'Odhiambo',
            'Akinyi',
            'Kamau',
            'Njeri',
            'Mutua',
            'Mwende',
            'Kipchoge',
            'Chebet',
            'Otieno',
            'Auma',
            'Kariuki',
            'Wambui',
            'Kosgei',
            'Jelagat',
            'Mwangi',
            'Njoki',
            'Kiplagat',
            'Kiprotich',
            'Ochieng',
            'Atieno',
            'Njuguna',
            'Wairimu',
            'Odongo',
            'Adhiambo',
            'Kipkorir',
            'Chepkorir',
            'Ouma',
            'Awino',
            'Kemboi',
            'Chemutai',
            'Okoth',
            'Achieng',
            'Kipkemoi',
            'Chepngetich',
            'Onyango',
            'Anyango',
            'Kimutai',
            'Jepchirchir',
            'Kiprop',
            'Cherono',
            'Rotich',
            'Jepkoech',
            'Langat',
            'Jepkemboi',
            'Keter',
            'Chepchumba',
            'Kirui',
            'Chelangat',
            'Koech',
            'Chepkwemoi',
            'Yego',
            'Cherotich',
            'Kiprono',
            'Chebet'
        ];

        $parentFirstNames = [
            'Peter',
            'Mary',
            'John',
            'Esther',
            'David',
            'Grace',
            'Paul',
            'Ruth',
            'James',
            'Sarah',
            'Joseph',
            'Elizabeth',
            'Charles',
            'Rose',
            'Thomas',
            'Jane',
            'Michael',
            'Susan',
            'Francis',
            'Agnes',
            'Simon',
            'Catherine',
            'Patrick',
            'Margaret'
        ];

        $admissionCounter = 1000;

        // Get all grade levels between 1 and 9
        $grades = GradeLevel::whereBetween('grade', [1, 9])->get();

        foreach ($grades as $grade) {
            // Fetch real ClassArms for this specific grade
            $classArms = ClassArm::where('grade_level_id', $grade->id)->get();

            if ($classArms->isEmpty()) {
                $this->command->warn("No ClassArms found for Grade {$grade->grade}. Skipping.");
                continue;
            }

            // Create 10 students per grade
            for ($i = 1; $i <= 10; $i++) {
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];

                // Simplified gender logic
                $maleNames = ['John', 'Peter', 'James', 'Joseph', 'David', 'Paul', 'Samuel', 'Alex', 'Brian', 'Kevin', 'Eric', 'Timothy', 'Patrick', 'Francis', 'Charles', 'George', 'Kennedy', 'Simon', 'Vincent', 'Boniface', 'Moses', 'Jacob', 'Philip', 'Julius', 'Michael', 'Stephen', 'Martin'];
                $gender = in_array($firstName, $maleNames) ? 'male' : 'female';

                // Calculate birth date based on grade (e.g., Grade 1 = ~6 yrs old)
                $birthYear = now()->year - (6 + ($grade->grade - 1));
                $birthDate = Carbon::create($birthYear, rand(1, 12), rand(1, 28));

                // Unique ID generation
                $upi = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1) . rand(10000000, 99999999));

                // Parent Logic
                $parentFirstName = $parentFirstNames[array_rand($parentFirstNames)];
                $parentLastName = $lastNames[array_rand($lastNames)];
                $isFather = in_array($parentFirstName, ['Peter', 'John', 'David', 'Paul', 'James', 'Joseph', 'Charles', 'Thomas', 'Michael', 'Francis', 'Simon', 'Patrick']);
                $parentPrefix = $isFather ? 'Mr.' : 'Mrs./Ms.';

                // Pick a REAL random class arm for this student
                $randomClassArm = $classArms->random();

                Student::create([
                    'upi_number' => $upi,
                    'admission_number' => 'ADM' . ($admissionCounter++),
                    'first_name' => $firstName,
                    'middle_name' => (rand(0, 1) ? $firstNames[array_rand($firstNames)] : null),
                    'last_name' => $lastName,
                    'date_of_birth' => $birthDate,
                    'gender' => $gender,
                    'birth_certificate_number' => 'B' . rand(10000000, 99999999),
                    'nationality' => 'Kenyan',

                    // Contact Information
                    'phone' => '07' . rand(10000000, 99999999),
                    'email' => strtolower($firstName . '.' . $lastName . '@example.com'),
                    'address' => 'P.O. Box ' . rand(100, 999) . '-' . rand(100, 999) . ', ' . $this->getRandomTown(),

                    // School Information
                    'current_grade_level' => $grade->grade,
                    'current_class' => $randomClassArm->name, // Uses the name from ClassArm (e.g., "A")

                    'enrollment_year' => now()->year - ($grade->grade - 1),
                    'graduation_year' => null,

                    // Parent/Guardian Information
                    'parent_name' => $parentPrefix . ' ' . $parentFirstName . ' ' . $parentLastName,
                    'parent_phone' => '07' . rand(10000000, 99999999),
                    'parent_email' => strtolower($parentFirstName . '.' . $parentLastName . '@example.com'),
                    'parent_relationship' => $isFather ? 'Father' : 'Mother',

                    // Status
                    'is_active' => true,
                    'is_graduated' => false,
                ]);
            }
        }

        // Special Case: Inactive Student
        Student::create([
            'upi_number' => 'INACTIVE01',
            'admission_number' => 'ADM' . ($admissionCounter++),
            'first_name' => 'Inactive',
            'last_name' => 'Student',
            'date_of_birth' => Carbon::create(2012, 5, 15),
            'gender' => 'male',
            'nationality' => 'Kenyan',
            'current_grade_level' => 7,
            'current_class' => 'A',
            'enrollment_year' => now()->year - 1,
            'parent_name' => 'Mr. Peter Omondi',
            'parent_phone' => '0712345678',
            'is_active' => false,
            'is_graduated' => false,
        ]);

        // Special Case: Graduated Student
        Student::create([
            'upi_number' => 'GRADUATE01',
            'admission_number' => 'ADM' . ($admissionCounter++),
            'first_name' => 'Graduated',
            'last_name' => 'Alumni',
            'date_of_birth' => Carbon::create(2005, 3, 20),
            'gender' => 'female',
            'nationality' => 'Kenyan',
            'current_grade_level' => 9,
            'current_class' => 'B',
            'enrollment_year' => 2021,
            'graduation_year' => 2025,
            'parent_name' => 'Mrs. Mary Wanjiku',
            'parent_phone' => '0798765432',
            'is_active' => true,
            'is_graduated' => true,
        ]);

        $this->command->info('Students seeded successfully!');
    }

    /**
     * Get a random Kenyan town.
     */
    private function getRandomTown(): string
    {
        $towns = [
            'Nairobi',
            'Mombasa',
            'Kisumu',
            'Nakuru',
            'Eldoret',
            'Thika',
            'Malindi',
            'Kitale',
            'Garissa',
            'Kakamega',
            'Machakos',
            'Meru',
            'Nyeri',
            'Kericho',
            'Bungoma',
            'Busia',
            'Kisii',
            'Kilifi',
            'Lamu',
            'Naivasha',
            'Narok',
            'Voi',
            'Wajir',
            'Mandera',
            'Marsabit',
            'Lodwar',
            'Isiolo',
            'Embu'
        ];

        return $towns[array_rand($towns)];
    }
}
