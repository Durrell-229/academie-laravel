<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseDetails;
use App\Models\CourseTag;
use App\Models\Lesson;
use App\Models\LessonTag;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Role;
use App\Models\User;

class InstructorDashboardController extends Controller
{
    // ──────────────────────────────────────────
    //  DASHBOARD
    // ──────────────────────────────────────────
    public function index()
    {
        $user      = Auth::user();
        $courses   = Course::where('user_id', $user->id)->latest()->get();
        $lessons   = Lesson::whereIn('course_id', $courses->pluck('id'))->latest()->get();
        $students  = \App\Models\Enrollment::whereIn('course_id', $courses->pluck('id'))->count();

        return view('instructor.dashboard', compact('courses', 'lessons', 'students'));
    }

    // ──────────────────────────────────────────
    //  COURS — Liste
    // ──────────────────────────────────────────
    public function courseIndex()
    {
        $courses = Course::where('user_id', Auth::id())->latest()->get();
        return view('instructor.courses.index', compact('courses'));
    }

    // ──────────────────────────────────────────
    //  COURS — Formulaire création
    // ──────────────────────────────────────────
    public function courseCreate()
    {
        $categories = Category::where('status', 1)->orderBy('title')->get();
        $topics     = Topic::orderBy('title')->get();
        return view('instructor.courses.create', compact('categories', 'topics'));
    }

    // ──────────────────────────────────────────
    //  COURS — Enregistrement
    // ──────────────────────────────────────────
    public function courseStore(Request $request)
    {
        $request->validate([
            'title'         => ['required', 'string', 'max:100', 'unique:courses,title'],
            'course_code'   => ['required', 'string', 'max:100', 'unique:courses,course_code'],
            'category_id'   => ['required', 'exists:categories,id'],
            'regular_price' => ['required', 'numeric', 'min:0'],
            'description'   => ['required', 'string'],
            'duration'      => ['required', 'string', 'max:50'],
            'difficulty'    => ['nullable', 'integer', 'between:1,5'],
            'thumbnail'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'title.required'       => 'Le titre du cours est obligatoire.',
            'title.unique'         => 'Ce titre est déjà utilisé.',
            'course_code.required' => 'Le code du cours est obligatoire.',
            'course_code.unique'   => 'Ce code est déjà utilisé.',
            'category_id.required' => 'Veuillez choisir une catégorie.',
            'regular_price.required' => 'Le prix est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'duration.required'    => 'La durée est obligatoire.',
        ]);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Course::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $course = Course::create([
            'user_id'       => Auth::id(),
            'category_id'   => $request->category_id,
            'title'         => $request->title,
            'course_code'   => $request->course_code,
            'regular_price' => $request->regular_price,
            'offer_price'   => $request->offer_price,
            'slug'          => $slug,
            'status'        => 0,
        ]);

        // Détails du cours
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('courses', 'public');
        }

        CourseDetails::create([
            'course_id'   => $course->id,
            'description' => $request->description,
            'highlights'  => $request->highlights,
            'duration'    => $request->duration,
            'difficulty'  => $request->difficulty,
            'thumbnail'   => $thumbnailPath,
        ]);

        // Tags / Topics
        if ($request->topic_id) {
            foreach ($request->topic_id as $topicId) {
                CourseTag::create([
                    'course_id' => $course->id,
                    'topic_id'  => $topicId,
                ]);
            }
        }

        return redirect()->route('instructor.courses.index')
            ->with('success', '✅ Cours "' . $course->title . '" créé avec succès !');
    }

    // ──────────────────────────────────────────
    //  COURS — Formulaire édition
    // ──────────────────────────────────────────
    public function courseEdit(Course $course)
    {
        $this->authorizeInstructor($course->user_id);
        $categories = Category::where('status', 1)->orderBy('title')->get();
        $topics     = Topic::orderBy('title')->get();
        return view('instructor.courses.edit', compact('course', 'categories', 'topics'));
    }

    // ──────────────────────────────────────────
    //  COURS — Mise à jour
    // ──────────────────────────────────────────
    public function courseUpdate(Request $request, Course $course)
    {
        $this->authorizeInstructor($course->user_id);

        $request->validate([
            'title'         => ['required', 'string', 'max:100', 'unique:courses,title,' . $course->id],
            'category_id'   => ['required', 'exists:categories,id'],
            'regular_price' => ['required', 'numeric', 'min:0'],
            'description'   => ['required', 'string'],
            'duration'      => ['required', 'string', 'max:50'],
        ]);

        $course->update([
            'category_id'   => $request->category_id,
            'title'         => $request->title,
            'regular_price' => $request->regular_price,
            'offer_price'   => $request->offer_price,
            'status'        => $request->status ?? $course->status,
        ]);

        $details = $course->details ?: new CourseDetails(['course_id' => $course->id]);
        $details->fill([
            'description' => $request->description,
            'highlights'  => $request->highlights,
            'duration'    => $request->duration,
            'difficulty'  => $request->difficulty,
        ]);
        $course->details()->save($details);

        return back()->with('success', '✅ Cours mis à jour avec succès !');
    }

    // ──────────────────────────────────────────
    //  COURS — Suppression
    // ──────────────────────────────────────────
    public function courseDestroy(Course $course)
    {
        $this->authorizeInstructor($course->user_id);
        $course->delete();
        return redirect()->route('instructor.courses.index')
            ->with('success', '🗑️ Cours supprimé.');
    }

    // ──────────────────────────────────────────
    //  LEÇONS — Liste
    // ──────────────────────────────────────────
    public function lessonIndex()
    {
        $courses = Course::where('user_id', Auth::id())->pluck('id');
        $lessons = Lesson::whereIn('course_id', $courses)->latest()->get();
        return view('instructor.lessons.index', compact('lessons'));
    }

    // ──────────────────────────────────────────
    //  LEÇONS — Formulaire création
    // ──────────────────────────────────────────
    public function lessonCreate()
    {
        $courses = Course::where('user_id', Auth::id())->where('status', 1)->get();
        $topics  = Topic::orderBy('title')->get();
        return view('instructor.lessons.create', compact('courses', 'topics'));
    }

    // ──────────────────────────────────────────
    //  LEÇONS — Enregistrement
    // ──────────────────────────────────────────
    public function lessonStore(Request $request)
    {
        $request->validate([
            'course_id'   => ['required', 'exists:courses,id'],
            'title'       => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'duration'    => ['required', 'string', 'max:50'],
        ], [
            'course_id.required'   => 'Veuillez choisir un cours.',
            'title.required'       => 'Le titre de la leçon est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'duration.required'    => 'La durée est obligatoire.',
        ]);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Lesson::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $lesson = Lesson::create([
            'course_id'   => $request->course_id,
            'title'       => $request->title,
            'description' => $request->description,
            'highlights'  => $request->highlights,
            'duration'    => $request->duration,
            'slug'        => $slug,
            'status'      => 0,
        ]);

        if ($request->topic_id) {
            foreach ($request->topic_id as $topicId) {
                LessonTag::create([
                    'lesson_id' => $lesson->id,
                    'topic_id'  => $topicId,
                ]);
            }
        }

        return redirect()->route('instructor.lessons.index')
            ->with('success', '✅ Leçon "' . $lesson->title . '" créée avec succès !');
    }

    // ──────────────────────────────────────────
    //  LEÇONS — Formulaire édition
    // ──────────────────────────────────────────
    public function lessonEdit(Lesson $lesson)
    {
        $this->authorizeInstructor($lesson->course->user_id ?? null);
        $courses = Course::where('user_id', Auth::id())->get();
        $topics  = Topic::orderBy('title')->get();
        return view('instructor.lessons.edit', compact('lesson', 'courses', 'topics'));
    }

    // ──────────────────────────────────────────
    //  LEÇONS — Mise à jour
    // ──────────────────────────────────────────
    public function lessonUpdate(Request $request, Lesson $lesson)
    {
        $this->authorizeInstructor($lesson->course->user_id ?? null);

        $request->validate([
            'title'       => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'duration'    => ['required', 'string', 'max:50'],
        ]);

        $lesson->update($request->only(['title', 'description', 'highlights', 'duration', 'status']));
        return back()->with('success', '✅ Leçon mise à jour !');
    }

    // ──────────────────────────────────────────
    //  LEÇONS — Suppression
    // ──────────────────────────────────────────
    public function lessonDestroy(Lesson $lesson)
    {
        $this->authorizeInstructor($lesson->course->user_id ?? null);
        $lesson->delete();
        return redirect()->route('instructor.lessons.index')
            ->with('success', '🗑️ Leçon supprimée.');
    }

    // ──────────────────────────────────────────
    //  HELPER — Vérifier que l'instructeur est propriétaire
    // ──────────────────────────────────────────
    private function authorizeInstructor(?int $ownerId): void
    {
        if ($ownerId !== Auth::id()) {
            abort(403, 'Accès refusé — ce contenu ne vous appartient pas.');
        }
    }
}

class InstructorController extends Controller
{
    public function index()
    {
        $roleProf    = Role::where('slug', 'professeur')->first();
        $instructors = $roleProf
            ? User::where('role_id', $roleProf->id)->with('courses')->get()
            : collect();

        return view('admin.instructor.index', compact('instructors'));
    }

    public function create() { return redirect()->route('users.create'); }
    public function store(Request $request) { return redirect()->route('instructor.index'); }
    public function show($id) { return redirect()->route('instructor.index'); }
    public function edit($id) { return redirect()->route('users.edit', $id); }
    public function update(Request $request, $id) { return redirect()->route('instructor.index'); }
    public function destroy($id) { return redirect()->route('instructor.index'); }
}
