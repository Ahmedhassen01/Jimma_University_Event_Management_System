<?php
// app/Http/Controllers/SpeakerController.php - Add event assignment methods

namespace App\Http\Controllers;

use App\Models\Speaker;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SpeakerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Speaker::query();

        // Apply search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Filter by active status
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by featured
        if ($request->filled('featured') && $request->featured == '1') {
            $query->where('is_featured', true);
        }

        // Apply sorting
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'asc');
        $query->orderBy($sort, $order);

        // Get statistics
        $totalCount = Speaker::count();
        $activeCount = Speaker::where('is_active', true)->count();
        $featuredCount = Speaker::where('is_featured', true)->where('is_active', true)->count();
        
        // Get upcoming talks count
        $upcomingTalks = Event::upcoming()
            ->whereHas('speakers')
            ->count();

        // Get paginated results with events count
        $speakers = $query->withCount('events')->paginate(20)->withQueryString();

        return view('admin.speakers.index', compact(
            'speakers',
            'totalCount',
            'activeCount',
            'featuredCount',
            'upcomingTalks'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.speakers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'email' => 'required|email|unique:speakers,email',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'expertise' => 'nullable|string',
            'website' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'twitter' => 'nullable|url',
            'photo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        // Generate slug from name
        $validated['slug'] = Str::slug($validated['name']);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('speakers', 'public');
            $validated['photo'] = $photoPath;
        }

        Speaker::create($validated);

        return redirect()->route('speakers.index')
            ->with('success', 'Speaker created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Speaker $speaker)
    {
        $speaker->load('events');
        return view('admin.speakers.show', compact('speaker'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Speaker $speaker)
    {
        return view('admin.speakers.edit', compact('speaker'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Speaker $speaker)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'email' => 'required|email|unique:speakers,email,' . $speaker->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'expertise' => 'nullable|string',
            'website' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'twitter' => 'nullable|url',
            'photo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        // Update slug if name changed
        if ($speaker->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('speakers', 'public');
            $validated['photo'] = $photoPath;

            // Delete old photo if exists
            if ($speaker->photo) {
                Storage::disk('public')->delete($speaker->photo);
            }
        }

        $speaker->update($validated);

        return redirect()->route('speakers.show', $speaker)
            ->with('success', 'Speaker updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Speaker $speaker)
    {
        // Check if speaker is assigned to any events
        if ($speaker->events()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete speaker because they are assigned to ' . $speaker->events()->count() . ' events. Please remove them from events first.');
        }

        // Delete photo if exists
        if ($speaker->photo) {
            Storage::disk('public')->delete($speaker->photo);
        }

        $speaker->delete();

        return redirect()->route('speakers.index')
            ->with('success', 'Speaker deleted successfully.');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Speaker $speaker)
    {
        $speaker->update(['is_active' => !$speaker->is_active]);

        return redirect()->back()
            ->with('success', 'Speaker status updated successfully.');
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Speaker $speaker)
    {
        $speaker->update(['is_featured' => !$speaker->is_featured]);

        return redirect()->back()
            ->with('success', 'Featured status updated successfully.');
    }

    /**
     * Get events for this speaker
     */
    public function events(Speaker $speaker)
    {
        $events = $speaker->events()->paginate(20);
        return view('admin.speakers.events', compact('speaker', 'events'));
    }

    /**
     * Remove speaker from an event
     */
    public function removeFromEvent(Speaker $speaker, Event $event)
    {
        $speaker->events()->detach($event->id);

        return redirect()->back()
            ->with('success', 'Speaker removed from event successfully.');
    }
}