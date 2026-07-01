<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\LiveSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiveSessionController extends Controller
{
    /**
     * Créer une session live
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id'    => ['required', 'exists:courses,id'],
            'title'        => ['required', 'string', 'max:150'],
            'platform'     => ['required', 'in:whatsapp,zoom,meet'],
            'link'         => ['nullable', 'url'],
            'scheduled_at' => ['nullable', 'date'],
        ]);

        $platform = $request->platform;
        $link     = $request->link;

        // Générer le lien automatiquement si pas fourni
        if (!$link) {
            $course  = Course::find($request->course_id);
            $message = urlencode('📚 Cours en direct : ' . $course->title . ' — Rejoignez maintenant !');

            $link = match($platform) {
                'whatsapp' => 'https://wa.me/?text=' . $message,
                'zoom'     => 'https://zoom.us/start/videomeeting',
                'meet'     => 'https://meet.google.com/new',
            };
        }

        $session = LiveSession::create([
            'course_id'    => $request->course_id,
            'user_id'      => Auth::id(),
            'title'        => $request->title,
            'platform'     => $platform,
            'link'         => $link,
            'scheduled_at' => $request->scheduled_at,
            'is_active'    => true,
        ]);

        return response()->json([
            'success' => true,
            'link'    => $session->link,
            'message' => 'Session créée !',
        ]);
    }

    /**
     * Lancer directement une session live
     */
    public function launch(Request $request)
    {
        $platform = $request->platform;
        $courseId = $request->course_id;
        $course   = Course::find($courseId);

        $message = urlencode('📚 Cours en direct : ' . ($course->title ?? 'Cours') . ' — Rejoignez maintenant !');

        $link = match($platform) {
            'whatsapp' => 'https://wa.me/?text=' . $message,
            'zoom'     => 'https://zoom.us/start/videomeeting',
            'meet'     => 'https://meet.google.com/new',
            default    => '#',
        };

        // Enregistrer la session
        LiveSession::create([
            'course_id'    => $courseId,
            'user_id'      => Auth::id(),
            'title'        => 'Cours en direct — ' . now()->format('d/m/Y H:i'),
            'platform'     => $platform,
            'link'         => $link,
            'scheduled_at' => now(),
            'is_active'    => true,
        ]);

        return response()->json([
            'success' => true,
            'link'    => $link,
        ]);
    }
}
