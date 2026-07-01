<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseDetails;
use App\Models\CourseTag;
use App\Models\Role;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['user', 'category', 'lessons', 'details'])->latest()->get();
        return view('admin.course.index', compact('courses'));
    }

    public function create()
    {
        $roleProf   = Role::where('slug', 'professeur')->first();
        $users      = $roleProf
            ? User::where('role_id', $roleProf->id)->where('status', 1)->get(['firstname', 'lastname', 'id'])
            : collect();
        $categories = Category::orderBy('title')->get(['title', 'parent_id', 'id']);
        $topics     = Topic::all();
        return view('admin.course.create', compact('categories', 'users', 'topics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => ['required', 'string', 'max:100', 'unique:courses,title'],
            'course_code'   => ['required', 'string', 'max:100', 'unique:courses,course_code'],
            'category_id'   => ['required', 'exists:categories,id'],
            'user_id'       => ['required', 'exists:users,id'],
            'regular_price' => ['required', 'numeric', 'min:0'],
            'description'   => ['required', 'string'],
            'thumbnail'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $slug = Str::slug($request->title);
        $base = $slug; $i = 1;
        while (Course::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        $course = Course::create([
            'user_id'       => $request->user_id,
            'category_id'   => $request->category_id,
            'title'         => $request->title,
            'course_code'   => $request->course_code,
            'regular_price' => $request->regular_price,
            'offer_price'   => $request->offer_price,
            'slug'          => $slug,
            'status'        => $request->status ?? 0,
        ]);

        $thumbnailPath = $request->hasFile('thumbnail')
            ? $request->file('thumbnail')->store('courses/thumbnails', 'public')
            : null;

        CourseDetails::create([
            'course_id'   => $course->id,
            'description' => $request->description,
            'highlights'  => $request->highlights,
            'duration'    => $request->duration,
            'difficulty'  => $request->difficulty,
            'thumbnail'   => $thumbnailPath,
        ]);

        return redirect()->route('courses.index')
            ->with('success', '✅ Cours "' . $course->title . '" créé !');
    }

    public function edit(Course $course)
    {
        $roleProf   = Role::where('slug', 'professeur')->first();
        $users      = $roleProf
            ? User::where('role_id', $roleProf->id)->where('status', 1)->get(['firstname', 'lastname', 'id'])
            : collect();
        $categories = Category::orderBy('title')->get(['title', 'parent_id', 'id']);
        $topics     = Topic::all();
        return view('admin.course.edit', compact('course', 'categories', 'users', 'topics'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title'         => ['required', 'string', 'max:100', 'unique:courses,title,' . $course->id],
            'category_id'   => ['required', 'exists:categories,id'],
            'regular_price' => ['required', 'numeric', 'min:0'],
            'description'   => ['required', 'string'],
        ]);

        $course->update([
            'user_id'       => $request->user_id ?? $course->user_id,
            'category_id'   => $request->category_id,
            'title'         => $request->title,
            'regular_price' => $request->regular_price,
            'offer_price'   => $request->offer_price,
            'status'        => $request->status ?? $course->status,
        ]);

        $details = $course->details ?? new CourseDetails(['course_id' => $course->id]);

        if ($request->hasFile('thumbnail')) {
            if ($details->thumbnail) Storage::disk('public')->delete($details->thumbnail);
            $details->thumbnail = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $details->fill([
            'description' => $request->description,
            'highlights'  => $request->highlights,
            'duration'    => $request->duration,
            'difficulty'  => $request->difficulty,
        ]);

        $course->details()->save($details);

        return redirect()->route('courses.index')
            ->with('success', '✅ Cours mis à jour !');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('courses.index')
            ->with('success', '🗑️ Cours supprimé.');
    }

    public function show(Course $course)
    {
        return view('admin.course.show', compact('course'));
    }
}
