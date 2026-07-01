<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursePayment;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Inscriptions
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with(['course', 'course.details', 'course.category', 'course.lessons'])
            ->latest()->get();

        $totalCours    = $enrollments->count();
        $coursTermines = $enrollments->where('status', 2)->count();
        $coursEnCours  = $enrollments->where('status', 1)->count();
        $progression   = $totalCours > 0
            ? round(($coursTermines / $totalCours) * 100) : 0;

        // Paiements en attente
        $pendingPayments = CoursePayment::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with('course')
            ->get();

        // Cours disponibles (non inscrits, non en attente de paiement)
        $enrolledIds     = $enrollments->pluck('course_id');
        $pendingCourseIds = $pendingPayments->pluck('course_id');
        $excludeIds      = $enrolledIds->merge($pendingCourseIds)->unique();

        $coursDisponibles = Course::where('status', 1)
            ->whereNotIn('id', $excludeIds)
            ->with(['details', 'category', 'lessons'])
            ->latest()->take(6)->get();

        return view('student.dashboard', compact(
            'user', 'enrollments', 'totalCours',
            'coursTermines', 'coursEnCours',
            'progression', 'coursDisponibles',
            'pendingPayments'
        ));
    }
}
