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
        $currentTerm = $this->getCurrentTerm();

        $stats = [
            'total_students' => Student::count(),
            'total_subjects' => LearningArea::count(),
            'pending_marks' => $this->getPendingMarksCount(),
            'recent_entries' => $this->getRecentEntries(),
            'classes' => $this->getTeacherClasses(),
            'performance_summary' => $this->getPerformanceSummary(),
            'current_term' => $currentTerm,
            'recent_students' => $this->getRecentStudents(), // ADDED
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

        $totalStudents = Student::count();
        $totalSubjects = LearningArea::count();

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
                    'student_id' => $record->student ? $record->student->id : null, // ADDED
                    'student_name' => $record->student ? $record->student->full_name : 'Unknown',
                    'subject' => $record->learningArea ? $record->learningArea->name : 'Unknown',
                    'score' => $record->score,
                    'grade' => $this->convertToGrade($record->score),
                    'date' => $record->created_at->format('M d, Y'),
                ];
            });
    }

    /**
     * Get recent students for quick navigation
     */
    private function getRecentStudents()
    {
        return Student::latest()
            ->take(5)
            ->get()
            ->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->full_name,
                    'grade' => $student->current_grade_level,
                ];
            });
    }

    /**
     * Get teacher's classes
     */
    private function getTeacherClasses()
    {
        // For now, return all grades 7-9 with student counts
        $classes = [];
        for ($grade = 7; $grade <= 9; $grade++) {
            $classes[] = [
                'grade' => $grade,
                'class' => $grade . 'A',
                'students' => Student::where('current_grade_level', $grade)->count(),
            ];
        }
        return $classes;
    }

    /**
     * Get performance summary by subject
     */
    private function getPerformanceSummary()
    {
        $currentTerm = $this->getCurrentTerm();
        $currentYear = date('Y');

        $subjects = LearningArea::take(4)->get();

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
     * Get current term
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
