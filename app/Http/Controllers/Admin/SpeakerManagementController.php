<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Speaker;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SpeakerManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage_events');
    }

    /**
     * Display a listing of speakers
     */
    public function index(Request $request)
    {
        $query = Speaker::query();
        
        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'featured') {
                $query->where('is_featured', true);
            }
        }
        
        // Apply sorting
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);
        
        $speakers = $query->paginate(20)->withQueryString();
        
        return view('admin.speakers.index', compact('speakers'));
    }

    /**
     * Show the form for creating a new speaker
     */
    public function create()
    {
        return view('admin.speakers.create');
    }

    /**
     * Store a newly created speaker
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'bio' => 'required|string',
            'photo' => 'nullable|image|max:2048',
            'website' => 'nullable|url',
            'twitter' => 'nullable|string|max:100',
            'linkedin' => 'nullable|url',
            'facebook' => 'nullable|url',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'expertise' => 'nullable|string',
        ]);

        // Generate slug
        $slug = Str::slug($validated['name']);
        $counter = 1;
        while (Speaker::where('slug', $slug)->exists()) {
            $slug = Str::slug($validated['name']) . '-' . $counter;
            $counter++;
        }
        $validated['slug'] = $slug;

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('speakers', 'public');
            $validated['photo'] = $path;
        }

        // Convert expertise from string to array
        if (!empty($validated['expertise'])) {
            $expertise = array_map('trim', explode(',', $validated['expertise']));
            $validated['expertise'] = json_encode($expertise);
        }

        // Create speaker
        $speaker = Speaker::create($validated);

        return redirect()->route('admin.speakers.index')
            ->with('success', 'Speaker created successfully.');
    }

    /**
     * Display the specified speaker
     */
    public function show(Speaker $speaker)
    {
        $speaker->load(['events' => function ($query) {
            $query->orderBy('start_date', 'desc');
        }]);
        
        $availableEvents = Event::where('is_public', true)
            ->where('start_date', '>', now())
            ->whereDoesntHave('speakers', function ($query) use ($speaker) {
                $query->where('speaker_id', $speaker->id);
            })
            ->get();
        
        return view('admin.speakers.show', compact('speaker', 'availableEvents'));
    }

    /**
     * Show the form for editing the specified speaker
     */
    public function edit(Speaker $speaker)
    {
        return view('admin.speakers.edit', compact('speaker'));
    }

    /**
     * Update the specified speaker
     */
    public function update(Request $request, Speaker $speaker)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'bio' => 'required|string',
            'photo' => 'nullable|image|max:2048',
            'website' => 'nullable|url',
            'twitter' => 'nullable|string|max:100',
            'linkedin' => 'nullable|url',
            'facebook' => 'nullable|url',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'expertise' => 'nullable|string',
        ]);

        // Update slug if name changed
        if ($speaker->name !== $validated['name']) {
            $slug = Str::slug($validated['name']);
            $counter = 1;
            while (Speaker::where('slug', $slug)->where('id', '!=', $speaker->id)->exists()) {
                $slug = Str::slug($validated['name']) . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($speaker->photo) {
                Storage::disk('public')->delete($speaker->photo);
            }
            
            $path = $request->file('photo')->store('speakers', 'public');
            $validated['photo'] = $path;
        }

        // Convert expertise from string to array
        if (!empty($validated['expertise'])) {
            $expertise = array_map('trim', explode(',', $validated['expertise']));
            $validated['expertise'] = json_encode($expertise);
        } else {
            $validated['expertise'] = null;
        }

        // Update speaker
        $speaker->update($validated);

        return redirect()->route('admin.speakers.show', $speaker)
            ->with('success', 'Speaker updated successfully.');
    }

    /**
     * Remove the specified speaker
     */
    public function destroy(Speaker $speaker)
    {
        // Delete photo if exists
        if ($speaker->photo) {
            Storage::disk('public')->delete($speaker->photo);
        }

        $speaker->delete();

        return redirect()->route('admin.speakers.index')
            ->with('success', 'Speaker deleted successfully.');
    }

    /**
     * Assign speaker to event
     */
    public function assignToEvent(Request $request, Speaker $speaker)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'session_title' => 'nullable|string|max:255',
            'session_time' => 'nullable|date',
            'session_duration' => 'nullable|integer|min:1',
            'session_description' => 'nullable|string',
            'is_keynote' => 'boolean',
        ]);

        $event = Event::findOrFail($validated['event_id']);
        
        // Check if already assigned
        if ($event->speakers()->where('speaker_id', $speaker->id)->exists()) {
            return back()->with('error', 'Speaker is already assigned to this event.');
        }

        // Attach speaker to event
        $event->speakers()->attach($speaker->id, [
            'session_title' => $validated['session_title'],
            'session_time' => $validated['session_time'],
            'session_duration' => $validated['session_duration'],
            'session_description' => $validated['session_description'],
            'is_keynote' => $validated['is_keynote'] ?? false,
            'order' => $event->speakers()->count(),
        ]);

        return back()->with('success', 'Speaker assigned to event successfully.');
    }

    /**
     * Remove speaker from event
     */
    public function removeFromEvent(Speaker $speaker, Event $event)
    {
        $event->speakers()->detach($speaker->id);

        return back()->with('success', 'Speaker removed from event successfully.');
    }

    /**
     * Update speaker event details
     */
    public function updateEventAssignment(Request $request, Speaker $speaker, Event $event)
    {
        $validated = $request->validate([
            'session_title' => 'nullable|string|max:255',
            'session_time' => 'nullable|date',
            'session_duration' => 'nullable|integer|min:1',
            'session_description' => 'nullable|string',
            'is_keynote' => 'boolean',
            'order' => 'integer',
        ]);

        $event->speakers()->updateExistingPivot($speaker->id, $validated);

        return back()->with('success', 'Speaker assignment updated successfully.');
    }

    /**
     * Toggle speaker status (active/inactive)
     */
    public function toggleStatus(Speaker $speaker)
    {
        $speaker->is_active = !$speaker->is_active;
        $speaker->save();

        $status = $speaker->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Speaker {$status} successfully.");
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Speaker $speaker)
    {
        $speaker->is_featured = !$speaker->is_featured;
        $speaker->save();

        $status = $speaker->is_featured ? 'featured' : 'unfeatured';
        return back()->with('success', "Speaker {$status} successfully.");
    }
}