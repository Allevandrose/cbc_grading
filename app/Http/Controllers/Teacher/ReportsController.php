<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\AcademicRecord;
use App\Models\GradeLevel;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Display a listing of students for report generation.
     */
    public function index(Request $request)
    {
        $query = Student::query();

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                    ->orWhere('middle_name', 'like', "%{$request->search}%")
                    ->orWhere('last_name', 'like', "%{$request->search}%")
                    ->orWhere('admission_number', 'like', "%{$request->search}%")
                    ->orWhere('upi_number', 'like', "%{$request->search}%");
            });
        }

        if ($request->has('grade') && $request->grade != '') {
            $query->where('current_grade_level', $request->grade);
        }

        $students = $query->paginate(15);
        $grades = GradeLevel::whereBetween('grade', [1, 9])->get();

        return view('teacher.reports.index', compact('students', 'grades'));
    }

    /**
     * Show report for a specific student.
     */
    public function show(Student $student)
    {
        $records = AcademicRecord::where('student_id', $student->id)
            ->with('learningArea')
            ->orderBy('year')
            ->orderBy('term')
            ->get()
            ->groupBy(['year', 'term']);

        // Calculate average performance
        $averages = [];
        foreach ($records as $year => $terms) {
            foreach ($terms as $term => $termRecords) {
                $total = $termRecords->sum('score');
                $count = $termRecords->count();
                $averages[$year][$term] = [
                    'average' => $count > 0 ? round($total / $count, 1) : 0,
                    'grade' => $this->convertToGrade($count > 0 ? $total / $count : 0),
                ];
            }
        }

        return view('teacher.reports.show', compact('student', 'records', 'averages'));
    }

    /**
     * Generate PDF report for a student.
     */
    public function generatePdf(Student $student)
    {
        // We'll implement PDF generation later with spatie/laravel-pdf
        return redirect()->back()->with('info', 'PDF generation coming soon!');
    }

    /**
     * Convert percentage to CBC grade
     */
    private function convertToGrade($percentage)
    {
        return match (true) {
            $percentage >= 90 => 'EE1',
            $percentage >= 75 => 'EE2',
            $percentage >= 58 => 'ME1',
            $percentage >= 41 => 'ME2',
            $percentage >= 31 => 'AE1',
            $percentage >= 21 => 'AE2',
            $percentage >= 11 => 'BE1',
            default => 'BE2',
        };
    }
}
