<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ClassArm;
use App\Models\GradeLevel;
use App\Models\LearningArea;
use App\Models\TeacherClassAssignment;
use App\Models\SubjectTeacherAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetupController extends Controller
{
    /**
     * Show the onboarding setup form.
     */
    public function create()
    {
        /** @var User $teacher */
        $teacher = Auth::user();

        if ($teacher->onboarding_completed_at) {
            return redirect()->route('teacher.dashboard');
        }

        $years = AcademicYear::where('is_current', true)->get();
        $subjects = LearningArea::all();

        // Grouping classes by Grade Level for a better UI experience
        $gradeLevels = GradeLevel::with('classArms')
            ->active()
            ->orderBy('grade')
            ->get();

        return view('teacher.setup', compact('years', 'gradeLevels', 'subjects'));
    }

    /**
     * Store the teacher's assignments and mark onboarding complete.
     */
    public function store(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_arm_ids'    => 'required|array',
            'class_arm_ids.*'  => 'exists:class_arms,id',
            'subject_ids'      => 'nullable|array',
            'subject_ids.*'    => 'exists:learning_areas,id',
        ]);

        $this->processAssignments($request);

        /** @var User $teacher */
        $teacher = Auth::user();
        $teacher->update(['onboarding_completed_at' => now()]);

        return redirect()->route('teacher.dashboard')
            ->with('success', 'Your teacher profile has been set up successfully!');
    }

    /**
     * Show the Settings page to update assignments.
     */
    public function settingsIndex()
    {
        /** @var User $teacher */
        $teacher = Auth::user();

        $years = AcademicYear::all();
        $subjects = LearningArea::all();
        $currentYearId = $this->getCurrentAcademicYearId();

        // Get assigned IDs for checkboxes using the teacher relationship
        $assignedClassIds = $teacher->teacherClassAssignments()
            ->where('academic_year_id', $currentYearId)
            ->where('is_active', true)
            ->pluck('class_arm_id')
            ->toArray();

        $assignedSubjectIds = $teacher->subjectTeacherAssignments()
            ->where('academic_year_id', $currentYearId)
            ->where('is_active', true)
            ->pluck('learning_area_id')
            ->toArray();

        // Load GradeLevels WITH their ClassArms for the selection UI
        $gradeLevels = GradeLevel::with('classArms')
            ->active()
            ->orderBy('grade')
            ->get();

        return view('teacher.settings', compact(
            'years',
            'gradeLevels',
            'subjects',
            'assignedClassIds',
            'assignedSubjectIds',
            'currentYearId'
        ));
    }

    /**
     * Update the teacher's settings.
     */
    public function settingsUpdate(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_arm_ids'    => 'required|array',
            'class_arm_ids.*'  => 'exists:class_arms,id',
            'subject_ids'      => 'nullable|array',
            'subject_ids.*'    => 'exists:learning_areas,id',
        ]);

        $this->processAssignments($request);

        return redirect()->route('teacher.settings.index')
            ->with('success', 'Settings updated successfully!');
    }

    /**
     * Helper to get the current academic year ID.
     */
    private function getCurrentAcademicYearId()
    {
        return AcademicYear::where('is_current', true)->first()?->id;
    }

    /**
     * Reusable logic for processing class and subject assignments.
     */
    private function processAssignments(Request $request)
    {
        $teacherId = Auth::id();
        $yearId = $request->academic_year_id;

        // 1. Sync Classes (Set existing to inactive first)
        TeacherClassAssignment::where('teacher_id', $teacherId)
            ->where('academic_year_id', $yearId)
            ->update(['is_active' => false]);

        foreach ($request->class_arm_ids as $classArmId) {
            $classArm = ClassArm::find($classArmId);
            TeacherClassAssignment::updateOrCreate(
                ['teacher_id' => $teacherId, 'class_arm_id' => $classArmId, 'academic_year_id' => $yearId],
                ['grade_level_id' => $classArm->grade_level_id ?? null, 'is_active' => true]
            );
        }

        // 2. Sync Subjects
        SubjectTeacherAssignment::where('teacher_id', $teacherId)
            ->where('academic_year_id', $yearId)
            ->update(['is_active' => false]);

        if ($request->filled('subject_ids')) {
            foreach ($request->class_arm_ids as $classArmId) {
                foreach ($request->subject_ids as $subjectId) {
                    SubjectTeacherAssignment::updateOrCreate(
                        ['teacher_id' => $teacherId, 'learning_area_id' => $subjectId, 'class_arm_id' => $classArmId, 'academic_year_id' => $yearId],
                        ['is_active' => true]
                    );
                }
            }
        }
    }
}
