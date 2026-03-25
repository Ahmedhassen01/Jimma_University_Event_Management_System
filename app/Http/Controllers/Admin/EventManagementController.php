<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Speaker;
use App\Models\EventRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EventManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage_events');
    }

    /**
     * Display a listing of events for management
     */
    public function index(Request $request)
    {
        $query = Event::query();
        
        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_public', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_public', false);
            } elseif ($request->status === 'featured') {
                $query->where('is_featured', true);
            }
        }
        
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }
        
        // Apply sorting
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);
        
        $events = $query->paginate(20)->withQueryString();
        
        $eventTypes = Event::distinct()->pluck('event_type');
        
        return view('admin.events.index', compact('events', 'eventTypes'));
    }

    /**
     * Show the form for creating a new event
     */
    public function create()
    {
        $speakers = Speaker::active()->get();
        return view('admin.events.create', compact('speakers'));
    }

    /**
     * Store a newly created event
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'venue' => 'required|string|max:255',
            'campus' => 'nullable|string|max:255',
            'event_type' => 'required|string|in:academic,cultural,sports,conference,workshop,seminar',
            'organizer' => 'required|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'max_attendees' => 'nullable|integer|min:1',
            'is_featured' => 'boolean',
            'is_public' => 'boolean',
            'requires_registration' => 'boolean',
            'registration_link' => 'nullable|url',
            'tags' => 'nullable|string',
            'speakers' => 'array',
            'speakers.*' => 'exists:speakers,id',
            'session_titles' => 'array',
            'session_times' => 'array',
            'session_durations' => 'array',
            'session_descriptions' => 'array',
            'is_keynote' => 'array',
        ]);

        // Generate slug
        $slug = Str::slug($validated['title']);
        $counter = 1;
        while (Event::where('slug', $slug)->exists()) {
            $slug = Str::slug($validated['title']) . '-' . $counter;
            $counter++;
        }
        $validated['slug'] = $slug;

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('events', 'public');
            $validated['featured_image'] = $path;
        }

        // Convert tags from string to array
        if (!empty($validated['tags'])) {
            $tags = array_map('trim', explode(',', $validated['tags']));
            $validated['tags'] = json_encode($tags);
        }

        // Create event
        $event = Event::create($validated);

        // Attach speakers
        if (!empty($validated['speakers'])) {
            $speakerData = [];
            foreach ($validated['speakers'] as $index => $speakerId) {
                $speakerData[$speakerId] = [
                    'session_title' => $validated['session_titles'][$index] ?? null,
                    'session_time' => $validated['session_times'][$index] ?? null,
                    'session_duration' => $validated['session_durations'][$index] ?? null,
                    'session_description' => $validated['session_descriptions'][$index] ?? null,
                    'is_keynote' => isset($validated['is_keynote'][$index]),
                    'order' => $index,
                ];
            }
            $event->speakers()->sync($speakerData);
        }

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified event
     */
    public function show(Event $event)
    {
        $event->load(['speakers']);
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event
     */
    public function edit(Event $event)
    {
        $speakers = Speaker::active()->get();
        $event->load(['speakers']);
        return view('admin.events.edit', compact('event', 'speakers'));
    }

    /**
     * Update the specified event
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'venue' => 'required|string|max:255',
            'campus' => 'nullable|string|max:255',
            'event_type' => 'required|string|in:academic,cultural,sports,conference,workshop,seminar',
            'organizer' => 'required|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'max_attendees' => 'nullable|integer|min:1',
            'is_featured' => 'boolean',
            'is_public' => 'boolean',
            'requires_registration' => 'boolean',
            'registration_link' => 'nullable|url',
            'tags' => 'nullable|string',
            'speakers' => 'array',
            'speakers.*' => 'exists:speakers,id',
            'session_titles' => 'array',
            'session_times' => 'array',
            'session_durations' => 'array',
            'session_descriptions' => 'array',
            'is_keynote' => 'array',
        ]);

        // Update slug if title changed
        if ($event->title !== $validated['title']) {
            $slug = Str::slug($validated['title']);
            $counter = 1;
            while (Event::where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
                $slug = Str::slug($validated['title']) . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($event->featured_image) {
                Storage::disk('public')->delete($event->featured_image);
            }
            
            $path = $request->file('featured_image')->store('events', 'public');
            $validated['featured_image'] = $path;
        }

        // Convert tags from string to array
        if (!empty($validated['tags'])) {
            $tags = array_map('trim', explode(',', $validated['tags']));
            $validated['tags'] = json_encode($tags);
        } else {
            $validated['tags'] = null;
        }

        // Update event
        $event->update($validated);

        // Update speakers
        if (isset($validated['speakers'])) {
            $speakerData = [];
            foreach ($validated['speakers'] as $index => $speakerId) {
                $speakerData[$speakerId] = [
                    'session_title' => $validated['session_titles'][$index] ?? null,
                    'session_time' => $validated['session_times'][$index] ?? null,
                    'session_duration' => $validated['session_durations'][$index] ?? null,
                    'session_description' => $validated['session_descriptions'][$index] ?? null,
                    'is_keynote' => isset($validated['is_keynote'][$index]),
                    'order' => $index,
                ];
            }
            $event->speakers()->sync($speakerData);
        } else {
            $event->speakers()->detach();
        }

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified event
     */
    public function destroy(Event $event)
    {
        // Delete featured image if exists
        if ($event->featured_image) {
            Storage::disk('public')->delete($event->featured_image);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }

    /**
     * Toggle event status (active/inactive)
     */
    public function toggleStatus(Event $event)
    {
        $event->is_public = !$event->is_public;
        $event->save();

        $status = $event->is_public ? 'activated' : 'deactivated';
        return back()->with('success', "Event {$status} successfully.");
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Event $event)
    {
        $event->is_featured = !$event->is_featured;
        $event->save();

        $status = $event->is_featured ? 'featured' : 'unfeatured';
        return back()->with('success', "Event {$status} successfully.");
    }
}