<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\LearningArea;
use App\Models\AcademicRecord;
use App\Models\GradeLevel;
use App\Models\Term;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarksController extends Controller
{
    /**
     * Display a listing of marks restricted to the teacher's domain.
     */
    public function index(Request $request)
    {
        /** @var User $teacher */
        $teacher = Auth::user();

        $myGradeIds = $teacher->my_grade_ids;
        $mySubjectIds = $teacher->my_subject_ids;

        $query = AcademicRecord::with(['student', 'learningArea'])
            ->whereIn('learning_area_id', $mySubjectIds);

        // Filter by grade
        if ($request->filled('grade')) {
            if ($myGradeIds->contains($request->grade)) {
                $query->where('grade_level', $request->grade);
            } else {
                abort(403, 'Unauthorized grade access.');
            }
        }

        // Filter by subject
        if ($request->filled('subject')) {
            if ($mySubjectIds->contains($request->subject)) {
                $query->where('learning_area_id', $request->subject);
            } else {
                abort(403, 'Unauthorized subject access.');
            }
        }

        if ($request->filled('term')) {
            $query->where('term', $request->term);
        }

        $marks = $query->latest()->paginate(15);

        $subjects = LearningArea::whereIn('id', $mySubjectIds)->get();
        $grades = GradeLevel::whereIn('id', $myGradeIds)->get();
        $terms = Term::where('year', date('Y'))->get();

        return view('teacher.marks.index', compact('marks', 'subjects', 'grades', 'terms'));
    }

    /**
     * Show selection form for entering marks.
     */
    public function select()
    {
        /** @var User $teacher */
        $teacher = Auth::user();

        $grades = GradeLevel::whereIn('id', $teacher->my_grade_ids)->get();
        $subjects = LearningArea::whereIn('id', $teacher->my_subject_ids)->get();
        $terms = Term::where('year', date('Y'))->get();

        return view('teacher.marks.select', compact('grades', 'subjects', 'terms'));
    }

    /**
     * Show the bulk entry form.
     */
    public function create(Request $request)
    {
        /** @var User $teacher */
        $teacher = Auth::user();

        $request->validate([
            'grade' => 'required|integer',
            'subject' => 'required|exists:learning_areas,id',
            'term' => 'required|integer|in:1,2,3',
        ]);

        if (!$teacher->my_grade_ids->contains($request->grade) || !$teacher->my_subject_ids->contains($request->subject)) {
            abort(403, 'Unauthorized access to this class or subject.');
        }

        $students = Student::where('current_grade_level', $request->grade)
            ->where('is_active', true)
            ->get();

        $subject = LearningArea::find($request->subject);

        $existingMarks = AcademicRecord::where('grade_level', $request->grade)
            ->where('learning_area_id', $request->subject)
            ->where('term', $request->term)
            ->where('year', date('Y'))
            ->get()
            ->keyBy('student_id');

        return view('teacher.marks.create', compact('students', 'subject', 'existingMarks', 'request'));
    }

    /**
     * Store bulk marks.
     */
    public function bulkStore(Request $request)
    {
        /** @var User $teacher */
        $teacher = Auth::user();

        if (!$teacher->my_grade_ids->contains($request->grade) || !$teacher->my_subject_ids->contains($request->subject_id)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'grade' => 'required|integer',
            'subject_id' => 'required|exists:learning_areas,id',
            'term' => 'required|integer|in:1,2,3',
            'marks' => 'required|array',
            'marks.*' => 'nullable|numeric|min:0|max:100',
        ]);

        $year = date('Y');
        $count = 0;

        foreach ($request->marks as $studentId => $score) {
            if ($score !== null && $score !== '') {
                AcademicRecord::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'learning_area_id' => $request->subject_id,
                        'grade_level' => $request->grade,
                        'term' => $request->term,
                        'year' => $year,
                    ],
                    [
                        'score' => $score,
                        'assessment_type' => 'CAT',
                    ]
                );
                $count++;
            }
        }

        return redirect()->route('teacher.marks.index')
            ->with('success', "{$count} marks saved successfully!");
    }

    public function edit(AcademicRecord $mark)
    {
        /** @var User $teacher */
        $teacher = Auth::user();

        if (!$teacher->my_subject_ids->contains($mark->learning_area_id)) {
            abort(403, 'You do not teach this subject.');
        }

        $mark->load(['student', 'learningArea']);
        return view('teacher.marks.edit', compact('mark'));
    }

    public function update(Request $request, AcademicRecord $mark)
    {
        /** @var User $teacher */
        $teacher = Auth::user();

        if (!$teacher->my_subject_ids->contains($mark->learning_area_id)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
        ]);

        $mark->update(['score' => $request->score]);

        return redirect()->route('teacher.marks.index')
            ->with('success', 'Mark updated successfully!');
    }

    public function destroy(AcademicRecord $mark)
    {
        /** @var User $teacher */
        $teacher = Auth::user();

        if (!$teacher->my_subject_ids->contains($mark->learning_area_id)) {
            abort(403, 'Unauthorized action.');
        }

        $mark->delete();

        return redirect()->route('teacher.marks.index')
            ->with('success', 'Mark deleted successfully!');
    }
}
