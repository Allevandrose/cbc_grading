<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\LearningArea;
use App\Models\AcademicRecord;
use App\Models\GradeLevel;
use App\Models\Term;
use Illuminate\Http\Request;

class MarksController extends Controller
{
    /**
     * Display a listing of marks.
     */
    public function index(Request $request)
    {
        $query = AcademicRecord::with(['student', 'learningArea']);

        if ($request->has('grade') && $request->grade != '') {
            $query->where('grade_level', $request->grade);
        }

        if ($request->has('subject') && $request->subject != '') {
            $query->where('learning_area_id', $request->subject);
        }

        if ($request->has('term') && $request->term != '') {
            $query->where('term', $request->term);
        }

        $marks = $query->latest()->paginate(15);
        $subjects = LearningArea::all();
        $grades = GradeLevel::whereBetween('grade', [1, 9])->get();
        $terms = Term::where('year', date('Y'))->get();

        return view('teacher.marks.index', compact('marks', 'subjects', 'grades', 'terms'));
    }

    /**
     * Show form to select grade, subject, and term before entering marks.
     */
    public function select()
    {
        $grades = GradeLevel::whereBetween('grade', [1, 9])->get();
        $subjects = LearningArea::all();
        $terms = Term::where('year', date('Y'))->get();

        return view('teacher.marks.select', compact('grades', 'subjects', 'terms'));
    }

    /**
     * Show form for creating new marks (bulk entry).
     */
    public function create(Request $request)
    {
        $request->validate([
            'grade' => 'required|integer|min:1|max:9',
            'subject' => 'required|exists:learning_areas,id',
            'term' => 'required|integer|in:1,2,3',
        ]);

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
     * Store marks in bulk.
     */
    public function bulkStore(Request $request)
    {
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

    /**
     * Show form for editing a single mark.
     */
    public function edit(AcademicRecord $mark)
    {
        $mark->load(['student', 'learningArea']);
        return view('teacher.marks.edit', compact('mark'));
    }

    /**
     * Update a single mark.
     */
    public function update(Request $request, AcademicRecord $mark)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
        ]);

        $mark->update([
            'score' => $request->score,
        ]);

        return redirect()->route('teacher.marks.index')
            ->with('success', 'Mark updated successfully!');
    }

    /**
     * Remove a mark.
     */
    public function destroy(AcademicRecord $mark)
    {
        $mark->delete();

        return redirect()->route('teacher.marks.index')
            ->with('success', 'Mark deleted successfully!');
    }
}
