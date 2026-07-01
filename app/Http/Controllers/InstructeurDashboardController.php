<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseDetails;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LiveSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InstructeurDashboardController extends Controller
{
    // ── Dashboard ──────────────────────────────────────────────
    public function index()
    {
        $user     = Auth::user();
        $courses  = Course::where('user_id', $user->id)->with(['lessons','category'])->latest()->get();
        $lessons  = Lesson::whereIn('course_id', $courses->pluck('id'))->latest()->get();
        $students = Enrollment::whereIn('course_id', $courses->pluck('id'))->count();

        return view('instructor.dashboard', compact('courses', 'lessons', 'students'));
    }

    // ── Apprenants ─────────────────────────────────────────────
    public function apprenants()
    {
        $user        = Auth::user();
        $courseIds   = Course::where('user_id', $user->id)->pluck('id');
        $enrollments = Enrollment::whereIn('course_id', $courseIds)->with(['user','course'])->get();
        return view('instructor.apprenants', compact('enrollments'));
    }

    // ════════════════════════════════════════════════════════════
    //  COURS
    // ════════════════════════════════════════════════════════════
    public function courseIndex()
    {
        $courses = Course::where('user_id', Auth::id())
            ->with(['category','lessons'])
            ->latest()->get();
        return view('instructor.courses.index', compact('courses'));
    }

    public function courseCreate()
    {
        $categories = Category::orderBy('title')->get();
        return view('instructor.courses.create', compact('categories'));
    }

    public function courseStore(Request $request)
    {
        $request->validate([
            'title'         => ['required','string','max:150'],
            'description'   => ['required','string'],
            'category_id'   => ['required','exists:categories,id'],
            'regular_price' => ['nullable','numeric','min:0'],
            'thumbnail'     => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        $slug = Str::slug($request->title);
        $base = $slug; $i = 1;
        while (Course::where('slug',$slug)->exists()) $slug = $base.'-'.$i++;

        // Code unique
        $code = strtoupper(Str::random(3)).'-'.rand(1000,9999);

        $course = Course::create([
            'user_id'       => Auth::id(),
            'category_id'   => $request->category_id,
            'title'         => $request->title,
            'course_code'   => $code,
            'slug'          => $slug,
            'regular_price' => $request->regular_price ?? 0,
            'offer_price'   => $request->offer_price,
            'status'        => $request->status ?? 0,
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')
                ->store('courses/thumbnails', 'public');
        }

        CourseDetails::create([
            'course_id'   => $course->id,
            'description' => $request->description,
            'highlights'  => $request->highlights,
            'duration'    => $request->duration,
            'difficulty'  => $request->difficulty,
            'thumbnail'   => $thumbnailPath,
        ]);

        return redirect()->route('instructor.courses.index')
            ->with('success', '✅ Cours "'.$course->title.'" créé !');
    }

    public function courseEdit(Course $course)
    {
        $this->authorCourse($course);
        $categories = Category::orderBy('title')->get();
        return view('instructor.courses.edit', compact('course','categories'));
    }

    public function courseUpdate(Request $request, Course $course)
    {
        $this->authorCourse($course);
        $request->validate([
            'title'         => ['required','string','max:150'],
            'description'   => ['required','string'],
            'regular_price' => ['nullable','numeric','min:0'],
            'thumbnail'     => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        $course->update([
            'category_id'   => $request->category_id ?? $course->category_id,
            'title'         => $request->title,
            'regular_price' => $request->regular_price ?? $course->regular_price,
            'offer_price'   => $request->offer_price,
            'status'        => $request->status ?? $course->status,
        ]);

        $details = $course->details ?? new CourseDetails(['course_id' => $course->id]);

        if ($request->hasFile('thumbnail')) {
            if ($details->thumbnail) Storage::disk('public')->delete($details->thumbnail);
            $details->thumbnail = $request->file('thumbnail')->store('courses/thumbnails','public');
        }

        $details->fill([
            'description' => $request->description,
            'highlights'  => $request->highlights,
            'duration'    => $request->duration,
            'difficulty'  => $request->difficulty,
        ]);
        $course->details()->save($details);

        return redirect()->route('instructor.courses.index')
            ->with('success', '✅ Cours mis à jour !');
    }

    public function courseDestroy(Course $course)
    {
        $this->authorCourse($course);
        $course->delete();
        return redirect()->route('instructor.courses.index')
            ->with('success', '🗑️ Cours supprimé.');
    }

    // ════════════════════════════════════════════════════════════
    //  LEÇONS
    // ════════════════════════════════════════════════════════════
    public function lessonIndex()
    {
        $courseIds = Course::where('user_id', Auth::id())->pluck('id');
        $lessons   = Lesson::whereIn('course_id', $courseIds)->with('course')->latest()->get();
        return view('instructor.lessons.index', compact('lessons'));
    }

    public function lessonCreate()
    {
        $courses = Course::where('user_id', Auth::id())->orderBy('title')->get();
        return view('instructor.lessons.create', compact('courses'));
    }

    public function lessonStore(Request $request)
    {
        $request->validate([
            'title'         => ['required','string','max:150'],
            'course_id'     => ['required','exists:courses,id'],
            'video_file'    => ['nullable','file','mimes:mp4,avi,mov,mkv','max:102400'],
            'pdf_file'      => ['nullable','file','mimes:pdf','max:20480'],
        ]);

        $slug = Str::slug($request->title);
        $base = $slug; $i = 1;
        while (Lesson::where('slug',$slug)->exists()) $slug = $base.'-'.$i++;

        $data = [
            'course_id'     => $request->course_id,
            'title'         => $request->title,
            'description'   => $request->description,
            'highlights'    => $request->highlights,
            'duration'      => $request->duration,
            'slug'          => $slug,
            'youtube_link'  => $request->youtube_link,
            'external_link' => $request->live_link ?? $request->external_link,
            'status'        => $request->status ?? 0,
        ];

        if ($request->hasFile('video_file')) {
            $data['video_file'] = $request->file('video_file')->store('lessons/videos','public');
        }
        if ($request->hasFile('pdf_file')) {
            $data['pdf_file'] = $request->file('pdf_file')->store('lessons/pdfs','public');
        }

        $lesson = Lesson::create($data);

        // Créer la session live si lien fourni
        if ($request->live_link) {
            $platform = str_contains($request->live_link,'whatsapp') ? 'whatsapp'
                : (str_contains($request->live_link,'zoom') ? 'zoom' : 'meet');
            LiveSession::create([
                'course_id'    => $request->course_id,
                'user_id'      => Auth::id(),
                'title'        => 'Live — '.$request->title,
                'platform'     => $platform,
                'link'         => $request->live_link,
                'scheduled_at' => now(),
                'is_active'    => true,
            ]);
        }

        return redirect()->route('instructor.lessons.index')
            ->with('success', '✅ Leçon créée !');
    }

    public function lessonEdit(Lesson $lesson)
    {
        $courses = Course::where('user_id', Auth::id())->orderBy('title')->get();
        return view('instructor.lessons.edit', compact('lesson','courses'));
    }

    public function lessonUpdate(Request $request, Lesson $lesson)
    {
        $request->validate([
            'title'      => ['required','string','max:150'],
            'video_file' => ['nullable','file','mimes:mp4,avi,mov,mkv','max:102400'],
            'pdf_file'   => ['nullable','file','mimes:pdf','max:20480'],
        ]);

        $data = [
            'course_id'     => $request->course_id ?? $lesson->course_id,
            'title'         => $request->title,
            'description'   => $request->description,
            'highlights'    => $request->highlights,
            'duration'      => $request->duration,
            'youtube_link'  => $request->youtube_link,
            'external_link' => $request->live_link ?? $request->external_link,
            'status'        => $request->status ?? $lesson->status,
        ];

        if ($request->hasFile('video_file')) {
            if ($lesson->video_file) Storage::disk('public')->delete($lesson->video_file);
            $data['video_file'] = $request->file('video_file')->store('lessons/videos','public');
        }
        if ($request->hasFile('pdf_file')) {
            if ($lesson->pdf_file) Storage::disk('public')->delete($lesson->pdf_file);
            $data['pdf_file'] = $request->file('pdf_file')->store('lessons/pdfs','public');
        }

        $lesson->update($data);

        // Mise à jour session live
        if ($request->live_link) {
            $platform = str_contains($request->live_link,'whatsapp') ? 'whatsapp'
                : (str_contains($request->live_link,'zoom') ? 'zoom' : 'meet');
            LiveSession::updateOrCreate(
                ['course_id' => $lesson->course_id, 'user_id' => Auth::id()],
                ['title' => 'Live — '.$lesson->title, 'platform' => $platform,
                 'link' => $request->live_link, 'is_active' => true]
            );
        }

        return redirect()->route('instructor.lessons.index')
            ->with('success', '✅ Leçon mise à jour !');
    }

    public function lessonDestroy(Lesson $lesson)
    {
        if ($lesson->video_file) Storage::disk('public')->delete($lesson->video_file);
        if ($lesson->pdf_file)   Storage::disk('public')->delete($lesson->pdf_file);
        $lesson->delete();
        return redirect()->route('instructor.lessons.index')
            ->with('success', '🗑️ Leçon supprimée.');
    }

    // ── Helper ─────────────────────────────────────────────────
    private function authorCourse(Course $course)
    {
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }
    }
}
