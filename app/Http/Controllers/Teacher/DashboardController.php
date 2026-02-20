<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\LearningArea;
use App\Models\AcademicRecord;
use App\Models\Term;
use App\Models\ClassArm;
use App\Models\GradeLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $teacher */
        $teacher = Auth::user();

        // Use the accessors defined in your User model
        $myGradeIds = $teacher->my_grade_ids;
        $myClassArmIds = $teacher->my_class_arm_ids; // Assuming this accessor exists for ClassArm IDs
        $mySubjectIds = $teacher->my_subject_ids;

        $currentTerm = $this->getCurrentTerm();

        // Count students specifically in the teacher's assigned grades
        $totalStudents = Student::whereIn('current_grade_level', $myGradeIds)->count();

        // Total subjects assigned
        $totalSubjects = $mySubjectIds->count();

        // Calculate marks made
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
            'total_students'      => $totalStudents,
            'total_subjects'      => $totalSubjects,
            'pending_marks'       => max(0, $expectedEntries - $entriesMade),
            'recent_entries'      => $this->getRecentEntries($mySubjectIds),
            'classes'             => $this->getTeacherClasses($myClassArmIds),
            'performance_summary' => $this->getPerformanceSummary($mySubjectIds, $currentTerm),
            'current_term'        => $currentTerm,
            'recent_students'     => $this->getRecentStudents($myGradeIds),
        ];

        return view('teacher.dashboard', compact('stats'));
    }

    /**
     * Get specific class arms and student counts for the teacher.
     */
    private function getTeacherClasses($myClassArmIds)
    {
        // 1. Get the ClassArm objects for the IDs assigned to the teacher
        $classArms = ClassArm::with('gradeLevel')->whereIn('id', $myClassArmIds)->get();

        $classes = [];

        foreach ($classArms as $arm) {
            // 2. Count students specifically in this Class Arm
            // We filter by the grade name/level AND the class arm name
            $count = Student::where('current_grade_level', $arm->gradeLevel->grade)
                ->where('current_class', $arm->name)
                ->count();

            $classes[] = [
                'grade'    => $arm->gradeLevel->grade,
                'class'    => $arm->name, // e.g., "7A"
                'full_name' => $arm->gradeLevel->grade . ' ' . $arm->name, // e.g., "Grade 7 A"
                'students' => $count,
            ];
        }

        return $classes;
    }

    private function getRecentEntries($mySubjectIds)
    {
        return AcademicRecord::with(['student', 'learningArea'])
            ->whereIn('learning_area_id', $mySubjectIds)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($record) {
                return [
                    'id'           => $record->id,
                    'student_id'   => $record->student?->id,
                    'student_name' => $record->student?->full_name ?? 'Unknown',
                    'subject'      => $record->learningArea?->name ?? 'Unknown',
                    'score'        => $record->score,
                    'grade'        => $this->convertToGrade($record->score),
                    'date'         => $record->created_at->format('M d, Y'),
                ];
            });
    }

    private function getRecentStudents($myGradeIds)
    {
        return Student::whereIn('current_grade_level', $myGradeIds)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($student) {
                return [
                    'id'    => $student->id,
                    'name'  => $student->full_name,
                    'grade' => $student->current_grade_level,
                ];
            });
    }

    private function getPerformanceSummary($mySubjectIds, $currentTerm)
    {
        $currentYear = date('Y');
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
                'grade'   => $this->convertToGrade($avgScore ?? 0),
            ];
        }

        return $summary;
    }

    private function getCurrentTerm()
    {
        $currentTerm = Term::where('is_current', true)->first();
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
            default           => 'BE2',
        };
    }
}
