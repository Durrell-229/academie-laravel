<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::with('course')->latest()->get();
        return view('admin.lesson.index', compact('lessons'));
    }

    public function create()
    {
        $courses = Course::where('status', 1)->orderBy('title')->get();
        return view('admin.lesson.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id'   => ['required', 'exists:courses,id'],
            'title'       => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
        ]);

        $slug = Str::slug($request->title);
        $base = $slug; $i = 1;
        while (Lesson::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        Lesson::create([
            'course_id'   => $request->course_id,
            'title'       => $request->title,
            'description' => $request->description,
            'highlights'  => $request->highlights,
            'duration'    => $request->duration,
            'slug'        => $slug,
            'status'      => $request->status ?? 0,
        ]);

        return redirect()->route('lessons.index')
            ->with('success', '✅ Leçon créée !');
    }

    public function edit(Lesson $lesson)
    {
        $courses = Course::where('status', 1)->orderBy('title')->get();
        return view('admin.lesson.edit', compact('lesson', 'courses'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
        ]);

        $lesson->update($request->only(['title', 'description', 'highlights', 'duration', 'status', 'course_id']));

        return redirect()->route('lessons.index')
            ->with('success', '✅ Leçon mise à jour !');
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        return redirect()->route('lessons.index')
            ->with('success', '🗑️ Leçon supprimée.');
    }

    public function show(Lesson $lesson)
    {
        return view('admin.lesson.show', compact('lesson'));
    }
}
