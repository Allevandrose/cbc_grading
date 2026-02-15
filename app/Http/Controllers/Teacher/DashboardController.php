<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\LearningArea;
use App\Models\AcademicRecord;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the teacher dashboard.
     */
    public function index()
    {
        // For now, we'll use static/demo data until we implement teacher-class relationships
        // In a real system, teachers would be assigned to specific classes/grades

        $stats = [
            'total_students' => Student::count(),
            'total_subjects' => LearningArea::count(),
            'pending_marks' => $this->getPendingMarksCount(),
            'recent_entries' => $this->getRecentEntries(),
            'classes' => $this->getTeacherClasses(),
            'performance_summary' => $this->getPerformanceSummary(),
        ];

        return view('teacher.dashboard', compact('stats'));
    }

    /**
     * Get pending marks count (marks not yet entered for current term)
     */
    private function getPendingMarksCount()
    {
        $currentTerm = $this->getCurrentTerm();
        $currentYear = date('Y');

        // Get total expected entries (students × subjects)
        $totalStudents = Student::count();
        $totalSubjects = LearningArea::forGrade(7)->count(); // Assuming Grade 7 for now

        // Get actual entries made
        $entriesMade = AcademicRecord::where('term', $currentTerm)
            ->where('year', $currentYear)
            ->count();

        $expectedEntries = $totalStudents * $totalSubjects;

        return max(0, $expectedEntries - $entriesMade);
    }

    /**
     * Get recent mark entries
     */
    private function getRecentEntries()
    {
        return AcademicRecord::with(['student', 'learningArea'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($record) {
                return [
                    'id' => $record->id,
                    'student_name' => $record->student->full_name ?? 'Unknown',
                    'subject' => $record->learningArea->name ?? 'Unknown',
                    'score' => $record->score,
                    'grade' => $this->convertToGrade($record->score),
                    'date' => $record->created_at->format('M d, Y'),
                ];
            });
    }

    /**
     * Get teacher's classes (simplified for now)
     */
    private function getTeacherClasses()
    {
        // In a real system, this would come from a teacher_class_assignments table
        // For now, return sample data
        return [
            ['grade' => 7, 'class' => '7A', 'students' => Student::inGrade(7)->count()],
            ['grade' => 8, 'class' => '8A', 'students' => Student::inGrade(8)->count()],
            ['grade' => 9, 'class' => '9A', 'students' => Student::inGrade(9)->count()],
        ];
    }

    /**
     * Get performance summary by subject
     */
    private function getPerformanceSummary()
    {
        $currentTerm = $this->getCurrentTerm();
        $currentYear = date('Y');

        $subjects = LearningArea::forGrade(7)->take(4)->get();

        $summary = [];
        foreach ($subjects as $subject) {
            $avgScore = AcademicRecord::where('learning_area_id', $subject->id)
                ->where('term', $currentTerm)
                ->where('year', $currentYear)
                ->avg('score');

            $summary[] = [
                'subject' => $subject->name,
                'average' => round($avgScore ?? 0, 1),
                'grade' => $this->convertToGrade($avgScore ?? 0),
            ];
        }

        return $summary;
    }

    /**
     * Get current term using Term model
     */
    private function getCurrentTerm()
    {
        $currentTerm = Term::getCurrent();
        return $currentTerm ? $currentTerm->term_number : 1;
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
