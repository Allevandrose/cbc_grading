<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\GradeLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index(Request $request)
    {
        $query = Student::query();

        // Search filter
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                    ->orWhere('middle_name', 'like', "%{$request->search}%")
                    ->orWhere('last_name', 'like', "%{$request->search}%")
                    ->orWhere('admission_number', 'like', "%{$request->search}%")
                    ->orWhere('upi_number', 'like', "%{$request->search}%");
            });
        }

        // Grade filter
        if ($request->has('grade') && $request->grade != '') {
            $query->where('current_grade_level', $request->grade);
        }

        // Status filter
        if ($request->has('status') && $request->status != '') {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        $students = $query->latest()->paginate(15);
        $grades = GradeLevel::whereBetween('grade', [1, 9])->get();

        // Stats for the top of the page
        $stats = [
            'total' => Student::count(),
            'active' => Student::where('is_active', true)->count(),
            'inactive' => Student::where('is_active', false)->count(),
            'graduated' => Student::where('is_graduated', true)->count(),
        ];

        return view('teacher.students.index', compact('students', 'grades', 'stats'));
    }

    /**
     * Show form for creating a new student.
     */
    public function create()
    {
        $grades = GradeLevel::whereBetween('grade', [1, 9])->get();
        $nextAdmissionNumber = $this->generateAdmissionNumber();

        return view('teacher.students.create', compact('grades', 'nextAdmissionNumber'));
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'nationality' => 'required|string|max:100',
            'admission_number' => 'required|string|unique:students',
            'upi_number' => 'required|string|unique:students',
            'current_grade_level' => 'required|integer|min:1|max:9',
            'current_class' => 'required|string|max:10',
            'enrollment_year' => 'required|integer|min:2000|max:' . date('Y'),
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'parent_email' => 'nullable|email|max:255',
            'parent_relationship' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $student = Student::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'nationality' => $request->nationality,
            'birth_certificate_number' => $request->birth_certificate_number,
            'admission_number' => $request->admission_number,
            'upi_number' => $request->upi_number,
            'current_grade_level' => $request->current_grade_level,
            'current_class' => $request->current_class,
            'enrollment_year' => $request->enrollment_year,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'parent_name' => $request->parent_name,
            'parent_phone' => $request->parent_phone,
            'parent_email' => $request->parent_email,
            'parent_relationship' => $request->parent_relationship,
            'is_active' => true,
            'is_graduated' => false,
        ]);

        return redirect()->route('teacher.students.index')
            ->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        $student->load('academicRecords.learningArea');

        $records = $student->academicRecords
            ->groupBy('year')
            ->map(function ($yearRecords) {
                return $yearRecords->groupBy('term');
            });

        return view('teacher.students.show', compact('student', 'records'));
    }

    /**
     * Show form for editing a student.
     */
    public function edit(Student $student)
    {
        $grades = GradeLevel::whereBetween('grade', [1, 9])->get();
        return view('teacher.students.edit', compact('student', 'grades'));
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, Student $student)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'nationality' => 'required|string|max:100',
            'admission_number' => 'required|string|unique:students,admission_number,' . $student->id,
            'upi_number' => 'required|string|unique:students,upi_number,' . $student->id,
            'current_grade_level' => 'required|integer|min:1|max:9',
            'current_class' => 'required|string|max:10',
            'enrollment_year' => 'required|integer|min:2000|max:' . date('Y'),
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'parent_email' => 'nullable|email|max:255',
            'parent_relationship' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $student->update($request->all());

        return redirect()->route('teacher.students.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Toggle student active status.
     */
    public function toggleActive(Student $student)
    {
        $student->update([
            'is_active' => !$student->is_active
        ]);

        $status = $student->is_active ? 'activated' : 'deactivated';

        return redirect()->route('teacher.students.index')
            ->with('success', "Student {$status} successfully.");
    }

    /**
     * Mark student as graduated.
     */
    public function markGraduated(Student $student)
    {
        $student->update([
            'is_graduated' => true,
            'is_active' => false,
            'graduation_year' => date('Y'),
        ]);

        return redirect()->route('teacher.students.index')
            ->with('success', 'Student marked as graduated.');
    }

    /**
     * Remove the specified student.
     */
    public function destroy(Student $student)
    {
        if ($student->academicRecords()->count() > 0) {
            return redirect()->route('teacher.students.index')
                ->with('error', 'Cannot delete student with academic records. Deactivate instead.');
        }

        $student->delete();

        return redirect()->route('teacher.students.index')
            ->with('success', 'Student deleted successfully.');
    }

    /**
     * Generate a unique admission number.
     */
    private function generateAdmissionNumber()
    {
        $year = date('Y');
        $lastStudent = Student::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastStudent) {
            $lastNumber = intval(substr($lastStudent->admission_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return 'ADM' . $year . $newNumber;
    }
}
