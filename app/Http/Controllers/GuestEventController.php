<?php
// app/Http/Controllers/GuestEventController.php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Campus;
use App\Models\Speaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GuestEventController extends Controller
{
    /**
     * Serve event image from storage/public paths.
     */
    public function image(Event $event)
    {
        if (!$event->image) {
            $defaultPath = public_path('images/default-event.jpg');
            if (file_exists($defaultPath)) {
                return response()->file($defaultPath);
            }
            abort(404);
        }

        $raw = str_replace('\\', '/', (string) $event->image);
        $normalized = ltrim($raw, '/');

        if (filter_var($normalized, FILTER_VALIDATE_URL)) {
            return redirect()->away($normalized);
        }

        if (str_starts_with($normalized, 'storage/')) {
            $normalized = substr($normalized, strlen('storage/'));
        } elseif (str_starts_with($normalized, '/storage/')) {
            $normalized = substr($normalized, strlen('/storage/'));
        }

        $candidates = array_values(array_unique(array_filter([
            $normalized,
            'events/' . basename($normalized),
            basename($normalized),
        ])));

        foreach ($candidates as $path) {
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->response($path);
            }
        }

        $publicCandidates = array_values(array_unique(array_filter([
            public_path($normalized),
            public_path('events/' . basename($normalized)),
            public_path('uploads/events/' . basename($normalized)),
            public_path('storage/' . $normalized),
            public_path('storage/events/' . basename($normalized)),
        ])));

        foreach ($publicCandidates as $path) {
            if (file_exists($path)) {
                return response()->file($path);
            }
        }

        $defaultPath = public_path('images/default-event.jpg');
        if (file_exists($defaultPath)) {
            return response()->file($defaultPath);
        }

        abort(404);
    }

    /**
     * Display events dashboard for guests (Homepage)
     */
    public function dashboard(Request $request)
    {
        // Start query for public events
        $query = Event::where('is_public', true)
                    ->where('end_date', '>=', now()->subDays(1))
                    ->with(['campusRelation', 'buildingRelation', 'venueRelation']);
        
        // Apply search filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('organizer', 'like', '%' . $request->search . '%')
                  ->orWhere('campus', 'like', '%' . $request->search . '%');
            });
        }
        
        // Apply event type filter
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }
        
        // Apply campus filter
        if ($request->filled('campus')) {
            $query->where('campus', 'like', '%' . $request->campus . '%');
        }
        
        // Apply status filter
        if ($request->filled('status')) {
            if ($request->status == 'upcoming') {
                $query->where('start_date', '>', now());
            } elseif ($request->status == 'ongoing') {
                $query->where('start_date', '<=', now())
                      ->where('end_date', '>=', now());
            } elseif ($request->status == 'past') {
                $query->where('end_date', '<', now());
            }
        }
        
        // Apply featured filter
        if ($request->filled('featured') && $request->featured == '1') {
            $query->where('is_featured', true);
        }
        
        // Apply sorting
        $sort = $request->get('sort', 'start_date');
        $order = $request->get('order', 'asc');
        
        if ($sort == 'popularity') {
            $query->orderBy('registered_attendees', 'desc');
        } elseif ($sort == 'recent') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->orderBy($sort, $order);
        }
        
        $events = $query->paginate(12)->withQueryString();
        
        // Get statistics
        $upcomingCount = Event::where('is_public', true)
                             ->where('start_date', '>', now())
                             ->count();
        
        $ongoingCount = Event::where('is_public', true)
                            ->where('start_date', '<=', now())
                            ->where('end_date', '>=', now())
                            ->count();
        
        $campusCount = Campus::where('is_active', true)->count();
        
        // Get unique campuses for filter dropdown
        $campuses = Event::where('is_public', true)
                        ->whereNotNull('campus')
                        ->select('campus')
                        ->distinct()
                        ->orderBy('campus')
                        ->pluck('campus')
                        ->filter()
                        ->values();
        
        // Get event types for filter
        $eventTypes = [
            'academic' => 'Academic',
            'cultural' => 'Cultural',
            'sports' => 'Sports',
            'conference' => 'Conference',
            'workshop' => 'Workshop',
            'seminar' => 'Seminar'
        ];
        
        // Get featured events for highlight
        $featuredEvents = Event::where('is_public', true)
                              ->where('is_featured', true)
                              ->where('end_date', '>=', now())
                              ->orderBy('start_date')
                              ->limit(3)
                              ->get();
        
        return view('events.guest.dashboard', compact(
            'events',
            'upcomingCount',
            'ongoingCount',
            'campusCount',
            'campuses',
            'eventTypes',
            'featuredEvents'
        ));
    }
    
    /**
     * Display single event details
     */
    public function show($slug)
    {
        $event = Event::where('slug', $slug)
                     ->where('is_public', true)
                     ->with(['campusRelation', 'buildingRelation', 'venueRelation'])
                     ->firstOrFail();
        
        // Increment view count
        $event->increment('views_count');
        
        // Get similar events (same type, upcoming)
        $similarEvents = Event::where('is_public', true)
                             ->where('event_type', $event->event_type)
                             ->where('id', '!=', $event->id)
                             ->where('end_date', '>=', now())
                             ->orderBy('start_date')
                             ->limit(3)
                             ->get();
        
        return view('events.guest.show', compact('event', 'similarEvents'));
    }
    
    /**
     * Browse all events with advanced filtering
     */
    public function browse(Request $request)
    {
        $query = Event::where('is_public', true)
                    ->with(['campusRelation', 'buildingRelation', 'venueRelation']);
        
        // Apply all filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('organizer', 'like', '%' . $request->search . '%')
                  ->orWhere('venue', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('campus')) {
            $query->where('campus', $request->campus);
        }
        
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }
        
        if ($request->filled('date_range')) {
            $now = now();
            if ($request->date_range == 'today') {
                $query->whereDate('start_date', $now->toDateString());
            } elseif ($request->date_range == 'week') {
                $query->whereBetween('start_date', [$now, $now->copy()->addDays(7)]);
            } elseif ($request->date_range == 'month') {
                $query->whereBetween('start_date', [$now, $now->copy()->addDays(30)]);
            } elseif ($request->date_range == 'upcoming') {
                $query->where('start_date', '>', $now);
            }
        }
        
        if ($request->filled('audience')) {
            // This would need an audience column in events table
            // $query->where('audience', $request->audience);
        }
        
        if ($request->filled('registration')) {
            if ($request->registration == 'required') {
                $query->where('requires_registration', true);
            } elseif ($request->registration == 'free') {
                $query->where('requires_registration', false);
            }
        }
        
        if ($request->filled('status')) {
            if ($request->status == 'upcoming') {
                $query->where('start_date', '>', now());
            } elseif ($request->status == 'ongoing') {
                $query->where('start_date', '<=', now())
                      ->where('end_date', '>=', now());
            } elseif ($request->status == 'completed') {
                $query->where('end_date', '<', now());
            }
        }
        
        // Apply sorting
        $sort = $request->get('sort', 'start_date');
        $order = $request->get('order', 'asc');
        
        if ($sort == 'popularity') {
            $query->orderBy('registered_attendees', 'desc');
        } elseif ($sort == 'recent') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->orderBy($sort, $order);
        }
        
        $events = $query->paginate(20)->withQueryString();
        
        // Get filter options
        $campuses = Event::where('is_public', true)
                        ->whereNotNull('campus')
                        ->select('campus')
                        ->distinct()
                        ->orderBy('campus')
                        ->pluck('campus')
                        ->filter()
                        ->values();
        
        $eventTypes = [
            'academic' => 'Academic',
            'cultural' => 'Cultural',
            'sports' => 'Sports',
            'conference' => 'Conference',
            'workshop' => 'Workshop',
            'seminar' => 'Seminar'
        ];
        
        return view('events.guest.browse', compact('events', 'campuses', 'eventTypes'));
    }
    
    /**
     * Share event page
     */
    public function share($slug)
    {
        $event = Event::where('slug', $slug)
                     ->where('is_public', true)
                     ->firstOrFail();
        
        // Generate share links
        $shareLinks = [
            'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode(route('events.guest.show', $slug)),
            'twitter' => 'https://twitter.com/intent/tweet?text=' . urlencode($event->title . ' - ' . route('events.guest.show', $slug)),
            'linkedin' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode(route('events.guest.show', $slug)),
            'whatsapp' => 'https://wa.me/?text=' . urlencode($event->title . ' - ' . route('events.guest.show', $slug)),
            'telegram' => 'https://t.me/share/url?url=' . urlencode(route('events.guest.show', $slug)) . '&text=' . urlencode($event->title),
            'email' => 'mailto:?subject=' . urlencode($event->title) . '&body=' . urlencode('Check out this event: ' . route('events.guest.show', $slug)),
        ];
        
        return view('events.guest.share', compact('event', 'shareLinks'));
    }
    
    /**
     * Export event to ICS calendar format
     */
    public function exportIcs($slug)
    {
        $event = Event::where('slug', $slug)
                     ->where('is_public', true)
                     ->firstOrFail();
        
        $icsContent = "BEGIN:VCALENDAR\r\n";
        $icsContent .= "VERSION:2.0\r\n";
        $icsContent .= "PRODID:-//Jimma University//Event Management//EN\r\n";
        $icsContent .= "BEGIN:VEVENT\r\n";
        $icsContent .= "UID:" . $event->id . "@ju.edu.et\r\n";
        $icsContent .= "DTSTAMP:" . now()->format('Ymd\THis') . "\r\n";
        $icsContent .= "DTSTART:" . $event->start_date->format('Ymd\THis') . "\r\n";
        $icsContent .= "DTEND:" . $event->end_date->format('Ymd\THis') . "\r\n";
        $icsContent .= "SUMMARY:" . $this->escapeIcs($event->title) . "\r\n";
        $icsContent .= "DESCRIPTION:" . $this->escapeIcs(strip_tags($event->description)) . "\r\n";
        $icsContent .= "LOCATION:" . $this->escapeIcs($event->venue . ', ' . $event->campus) . "\r\n";
        
        if ($event->contact_email) {
            $icsContent .= "ORGANIZER;CN=" . $this->escapeIcs($event->organizer) . ":MAILTO:" . $event->contact_email . "\r\n";
        }
        
        $icsContent .= "URL:" . route('events.guest.show', $slug) . "\r\n";
        $icsContent .= "END:VEVENT\r\n";
        $icsContent .= "END:VCALENDAR\r\n";
        
        return response($icsContent)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $event->slug . '.ics"');
    }
    
    /**
     * Escape special characters for ICS format
     */
    private function escapeIcs($string)
    {
        $string = str_replace(["\r\n", "\r", "\n"], "\\n", $string);
        $string = str_replace(',', '\,', $string);
        $string = str_replace(';', '\;', $string);
        return $string;
    }
}
