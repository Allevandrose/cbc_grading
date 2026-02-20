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
    public function index()
    {
        $teacher = Auth::user();
        $myGradeIds = $teacher->my_grade_ids; // Uses accessor from User model
        $mySubjectIds = $teacher->my_subject_ids; // Uses accessor from User model

        $currentTerm = $this->getCurrentTerm();

        // Only count students in the teacher's assigned grades
        $totalStudents = Student::whereIn('current_grade_level', $myGradeIds)->count();

        // Only count subjects assigned to the teacher
        $totalSubjects = $mySubjectIds->count();

        // Calculate pending marks ONLY for the teacher's subjects and grades
        $entriesMade = AcademicRecord::where('term', $currentTerm)
            ->where('year', date('Y'))
            ->whereIn('learning_area_id', $mySubjectIds)
            ->whereHas('student', function ($q) use ($myGradeIds) {
                $q->whereIn('current_grade_level', $myGradeIds);
            })
            ->count();

        // Expected entries = (Students in my grades) * (My Subjects)
        $expectedEntries = $totalStudents * $totalSubjects;

        $stats = [
            'total_students' => $totalStudents,
            'total_subjects' => $totalSubjects,
            'pending_marks' => max(0, $expectedEntries - $entriesMade),
            'recent_entries' => $this->getRecentEntries($mySubjectIds),
            'classes' => $this->getTeacherClasses($myGradeIds),
            'performance_summary' => $this->getPerformanceSummary($mySubjectIds, $currentTerm),
            'current_term' => $currentTerm,
            'recent_students' => $this->getRecentStudents($myGradeIds),
        ];

        return view('teacher.dashboard', compact('stats'));
    }

    private function getRecentEntries($mySubjectIds)
    {
        return AcademicRecord::with(['student', 'learningArea'])
            ->whereIn('learning_area_id', $mySubjectIds) // RESTRICTED
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($record) {
                return [
                    'id' => $record->id,
                    'student_id' => $record->student ? $record->student->id : null,
                    'student_name' => $record->student ? $record->student->full_name : 'Unknown',
                    'subject' => $record->learningArea ? $record->learningArea->name : 'Unknown',
                    'score' => $record->score,
                    'grade' => $this->convertToGrade($record->score),
                    'date' => $record->created_at->format('M d, Y'),
                ];
            });
    }

    private function getRecentStudents($myGradeIds)
    {
        return Student::whereIn('current_grade_level', $myGradeIds) // RESTRICTED
            ->latest()
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

    private function getTeacherClasses($myGradeIds)
    {
        // Only return classes that are assigned to the teacher
        $classes = [];
        // Fetch actual grade objects from IDs
        $grades = \App\Models\GradeLevel::whereIn('id', $myGradeIds)->get();

        foreach ($grades as $grade) {
            $count = Student::where('current_grade_level', $grade->grade)->count();
            $classes[] = [
                'grade' => $grade->grade,
                'class' => $grade->grade . 'A', // Adjust logic if you have specific class names
                'students' => $count,
            ];
        }
        return $classes;
    }

    private function getPerformanceSummary($mySubjectIds, $currentTerm)
    {
        $currentYear = date('Y');
        // Only get subjects assigned to teacher
        $subjects = LearningArea::whereIn('id', $mySubjectIds)->get();

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

    private function getCurrentTerm()
    {
        $currentTerm = Term::getCurrent();
        return $currentTerm ? $currentTerm->term_number : 1;
    }

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
