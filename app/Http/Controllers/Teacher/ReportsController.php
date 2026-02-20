<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\AcademicRecord;
use App\Models\GradeLevel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    /**
     * Display listing of students for report viewing.
     */
    public function index(Request $request)
    {
        /** @var User $teacher */
        $teacher = Auth::user();
        $myGradeIds = $teacher->my_grade_ids;

        $query = Student::query()->whereIn('current_grade_level', $myGradeIds);

        // Search filter using filled() for cleaner logic
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                    ->orWhere('middle_name', 'like', "%{$request->search}%")
                    ->orWhere('last_name', 'like', "%{$request->search}%")
                    ->orWhere('admission_number', 'like', "%{$request->search}%")
                    ->orWhere('upi_number', 'like', "%{$request->search}%");
            });
        }

        // Grade filter restricted to teacher's assignments
        if ($request->filled('grade') && $myGradeIds->contains($request->grade)) {
            $query->where('current_grade_level', $request->grade);
        }

        $students = $query->latest()->paginate(15);
        $grades = GradeLevel::whereIn('id', $myGradeIds)->get();

        return view('teacher.reports.index', compact('students', 'grades'));
    }

    /**
     * Show detailed academic report for a specific student.
     */
    public function show(Student $student)
    {
        /** @var User $teacher */
        $teacher = Auth::user();

        // Security Check
        if (!$teacher->my_grade_ids->contains($student->current_grade_level)) {
            abort(403, 'Unauthorized to view reports for this student.');
        }

        $records = AcademicRecord::where('student_id', $student->id)
            ->with('learningArea')
            ->orderBy('year')
            ->orderBy('term')
            ->get()
            ->groupBy(['year', 'term']);

        $averages = [];
        foreach ($records as $year => $terms) {
            foreach ($terms as $term => $termRecords) {
                $avgScore = $termRecords->avg('score') ?? 0;

                $averages[$year][$term] = [
                    'average' => round($avgScore, 1),
                    'grade'   => $this->convertToGrade($avgScore),
                ];
            }
        }

        return view('teacher.reports.show', compact('student', 'records', 'averages'));
    }

    /**
     * Placeholder for PDF Generation.
     */
    public function generatePdf(Student $student)
    {
        /** @var User $teacher */
        $teacher = Auth::user();

        if (!$teacher->my_grade_ids->contains($student->current_grade_level)) {
            abort(403);
        }

        return redirect()->back()->with('info', 'PDF generation is being architected and will be available soon!');
    }

    /**
     * Convert percentage score to PathCore-specific CBC grades.
     */
    private function convertToGrade($percentage)
    {
        return match (true) {
            $percentage >= 90 => 'EE1', // Exceeding Expectation 1
            $percentage >= 75 => 'EE2',
            $percentage >= 58 => 'ME1', // Meeting Expectation 1
            $percentage >= 41 => 'ME2',
            $percentage >= 31 => 'AE1', // Approaching Expectation 1
            $percentage >= 21 => 'AE2',
            $percentage >= 11 => 'BE1', // Below Expectation 1
            default          => 'BE2',
        };
    }
}
