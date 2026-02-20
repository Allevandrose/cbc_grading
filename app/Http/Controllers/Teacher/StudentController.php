<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\GradeLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of students restricted to teacher's assigned grades.
     */
    public function index(Request $request)
    {
        $teacher = Auth::user();
        $myGradeIds = $teacher->my_grade_ids; // Using the attribute from your User model

        $query = Student::query()->whereIn('current_grade_level', $myGradeIds);

        // Search filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                    ->orWhere('middle_name', 'like', "%{$request->search}%")
                    ->orWhere('last_name', 'like', "%{$request->search}%")
                    ->orWhere('admission_number', 'like', "%{$request->search}%")
                    ->orWhere('upi_number', 'like', "%{$request->search}%");
            });
        }

        // Grade filter (restricted to teacher's grades)
        if ($request->filled('grade') && $myGradeIds->contains($request->grade)) {
            $query->where('current_grade_level', $request->grade);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $students = $query->latest()->paginate(15);

        // Only show assigned grades in the dropdown
        $grades = GradeLevel::whereIn('id', $myGradeIds)->get();

        // Restricted stats
        $stats = [
            'total' => Student::whereIn('current_grade_level', $myGradeIds)->count(),
            'active' => Student::whereIn('current_grade_level', $myGradeIds)->where('is_active', true)->count(),
            'inactive' => Student::whereIn('current_grade_level', $myGradeIds)->where('is_active', false)->count(),
            'graduated' => Student::whereIn('current_grade_level', $myGradeIds)->where('is_graduated', true)->count(),
        ];

        return view('teacher.students.index', compact('students', 'grades', 'stats'));
    }

    /**
     * Show form for creating a new student.
     */
    public function create()
    {
        $teacher = Auth::user();
        $grades = GradeLevel::whereIn('id', $teacher->my_grade_ids)->get();
        $nextAdmissionNumber = $this->generateAdmissionNumber();

        return view('teacher.students.create', compact('grades', 'nextAdmissionNumber'));
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request)
    {
        $teacher = Auth::user();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'nationality' => 'required|string|max:100',
            'admission_number' => 'required|string|unique:students',
            'upi_number' => 'required|string|unique:students',
            'current_grade_level' => 'required|exists:grade_levels,id',
            'current_class' => 'required|string|max:10',
            'enrollment_year' => 'required|integer|min:2000|max:' . date('Y'),
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Security check: ensure student is being added to a grade the teacher actually teaches
        if (!$teacher->my_grade_ids->contains($request->current_grade_level)) {
            return redirect()->back()->with('error', 'Unauthorized grade level selection.')->withInput();
        }

        Student::create(array_merge($request->all(), [
            'is_active' => true,
            'is_graduated' => false
        ]));

        return redirect()->route('teacher.students.index')->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        $this->authorizeTeacherAccess($student);

        $student->load('academicRecords.learningArea');

        $records = $student->academicRecords
            ->groupBy('year')
            ->map(fn($yearRecords) => $yearRecords->groupBy('term'));

        return view('teacher.students.show', compact('student', 'records'));
    }

    /**
     * Show form for editing a student.
     */
    public function edit(Student $student)
    {
        $this->authorizeTeacherAccess($student);

        $teacher = Auth::user();
        $grades = GradeLevel::whereIn('id', $teacher->my_grade_ids)->get();

        return view('teacher.students.edit', compact('student', 'grades'));
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, Student $student)
    {
        $this->authorizeTeacherAccess($student);
        $teacher = Auth::user();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'admission_number' => 'required|string|unique:students,admission_number,' . $student->id,
            'upi_number' => 'required|string|unique:students,upi_number,' . $student->id,
            'current_grade_level' => 'required|exists:grade_levels,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Security check for moving student to a new grade
        if (!$teacher->my_grade_ids->contains($request->current_grade_level)) {
            return redirect()->back()->with('error', 'You cannot move a student to a grade you do not teach.');
        }

        $student->update($request->all());

        return redirect()->route('teacher.students.index')->with('success', 'Student updated successfully.');
    }

    /**
     * Toggle student active status.
     */
    public function toggleActive(Student $student)
    {
        $this->authorizeTeacherAccess($student);

        $student->update(['is_active' => !$student->is_active]);
        $status = $student->is_active ? 'activated' : 'deactivated';

        return redirect()->back()->with('success', "Student {$status} successfully.");
    }

    /**
     * Remove the specified student.
     */
    public function destroy(Student $student)
    {
        $this->authorizeTeacherAccess($student);

        if ($student->academicRecords()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete student with existing academic records.');
        }

        $student->delete();

        return redirect()->route('teacher.students.index')->with('success', 'Student record deleted.');
    }

    /**
     * Internal Helper to enforce security across methods.
     */
    private function authorizeTeacherAccess(Student $student)
    {
        if (!Auth::user()->my_grade_ids->contains($student->current_grade_level)) {
            abort(403, 'Access Denied: This student is not in your assigned classes.');
        }
    }

    /**
     * Generate a unique admission number.
     */
    private function generateAdmissionNumber()
    {
        $year = date('Y');
        $count = Student::whereYear('created_at', $year)->count() + 1;
        return 'ADM' . $year . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
