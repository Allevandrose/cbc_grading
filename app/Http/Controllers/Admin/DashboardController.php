<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\LearningArea;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get user stats
        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::where('role', User::ROLE_ADMIN)->count(),
            'total_teachers' => User::where('role', User::ROLE_TEACHER)->count(),
            'total_accountants' => User::where('role', User::ROLE_ACCOUNTANT)->count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
            'total_students' => Student::count(),
            'total_subjects' => LearningArea::count(),
            'recent_users' => User::latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
