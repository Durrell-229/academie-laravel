<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class SiteController extends Controller
{
    public function home()
    {
        return view('front-end.home');
    }

    public function about()
    {
        return view('front-end.about');
    }

    public function contact()
    {
        return view('front-end.contact');
    }

    public function team()
    {
        $instructors = User::whereHas('role', fn($q) => $q->where('slug', 'professeur'))
            ->where('status', 1)->get();
        return view('front-end.team', compact('instructors'));
    }

    public function instructor($uname)
    {
        $instructor = User::where('username', $uname)->firstOrFail();
        return view('front-end.instructor', compact('instructor'));
    }

    public function disclaimer()
    {
        return view('front-end.disclaimer');
    }

    public function privacy()
    {
        return view('front-end.privacy');
    }

    public function terms()
    {
        return view('front-end.terms');
    }

    public function faq()
    {
        return view('front-end.faq');
    }

    public function sitemap()
    {
        return view('front-end.sitemap');
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $courses  = Course::where('category_id', $category->id)->where('status', 1)->get();
        return view('front-end.courses', compact('category', 'courses'));
    }

    public function courses()
    {
        $categories = Category::all();
        $courses    = Course::where('status', 1)
            ->with(['details', 'category', 'user', 'lessons'])
            ->latest()->get();
        return view('front-end.courses', compact('categories', 'courses'));
    }

    public function display($slug)
    {
        $course = Course::with([
            'user', 'user.profile', 'category',
            'details', 'lessons', 'topic'
        ])->where('slug', $slug)->firstOrFail();

        $isEnrolled        = false;
        $hasPendingPayment = false;

        if (auth()->check()) {
            $isEnrolled = Enrollment::where('user_id', auth()->id())
                ->where('course_id', $course->id)->exists();

            if (!$isEnrolled && Schema::hasTable('course_payments')) {
                $hasPendingPayment = \App\Models\CoursePayment::where('user_id', auth()->id())
                    ->where('course_id', $course->id)
                    ->where('status', 'pending')->exists();
            }
        }

        $liveSessions = collect();
        if (Schema::hasTable('live_sessions')) {
            $liveSessions = \App\Models\LiveSession::where('course_id', $course->id)
                ->where('is_active', true)
                ->latest()->get();
        }

        return view('front-end.display', compact(
            'course', 'isEnrolled', 'hasPendingPayment', 'liveSessions'
        ));
    }

    public function enrollment(Request $request)
    {
        $courseId = $request->query('course_id');
        $course   = Course::find($courseId);
        return view('front-end.checkout', compact('course'));
    }

    public function processPayment(Request $request)
    {
        return redirect()->route('success');
    }

    public function success()
    {
        return view('front-end.success');
    }
}