<?php
// app/Http/Controllers/VenueController.php
namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\Building;
use App\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Event;

class VenueController extends Controller
{
    public function index(Request $request)
    {
        $query = Venue::query()->with(['building', 'building.campus']);
        
        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%")
                  ->orWhereHas('building', function ($q) use ($request) {
                      $q->where('name', 'like', "%{$request->search}%")
                        ->orWhere('code', 'like', "%{$request->search}%");
                  })
                  ->orWhereHas('building.campus', function ($q) use ($request) {
                      $q->where('name', 'like', "%{$request->search}%");
                  });
            });
        }
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Filter by campus
        if ($request->filled('campus_id')) {
            $query->whereHas('building', function ($q) use ($request) {
                $q->where('campus_id', $request->campus_id);
            });
        }
        
        // Filter by building
        if ($request->filled('building_id')) {
            $query->where('building_id', $request->building_id);
        }
        
        // Filter by availability
        if ($request->filled('availability')) {
            $query->where('is_available', $request->availability == 'available');
        }
        
        // Filter by capacity
        if ($request->filled('min_capacity')) {
            $query->where('capacity', '>=', $request->min_capacity);
        }
        
        $venues = $query->latest()->paginate(20);
        $campuses = Campus::active()->get();
        $buildings = Building::active()->get();
        
        $venueTypes = [
            'classroom' => 'Classroom',
            'auditorium' => 'Auditorium',
            'hall' => 'Hall',
            'lab' => 'Laboratory',
            'sports_complex' => 'Sports Complex',
            'conference_room' => 'Conference Room',
            'seminar_room' => 'Seminar Room',
            'open_space' => 'Open Space',
        ];
        
        return view('admin.venues.index', compact('venues', 'campuses', 'buildings', 'venueTypes'));
    }

    public function create()
    {
        $campuses = Campus::active()->get();
        $buildings = Building::active()->get();
        $venueTypes = [
            'classroom' => 'Classroom',
            'auditorium' => 'Auditorium',
            'hall' => 'Hall',
            'lab' => 'Laboratory',
            'sports_complex' => 'Sports Complex',
            'conference_room' => 'Conference Room',
            'seminar_room' => 'Seminar Room',
            'open_space' => 'Open Space',
        ];
        
        return view('admin.venues.create', compact('campuses', 'buildings', 'venueTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'building_id' => 'required|exists:buildings,id',
            'type' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'booking_fee' => 'nullable|numeric|min:0',
            'is_available' => 'boolean',
            'requires_approval' => 'boolean',
            'available_hours' => 'nullable|array',
        ]);

        $slug = Str::slug($validated['name']);
        $counter = 1;
        while (Venue::where('slug', $slug)->exists()) {
            $slug = Str::slug($validated['name']) . '-' . $counter;
            $counter++;
        }
        $validated['slug'] = $slug;

        Venue::create($validated);

        return redirect()->route('admin.venues.index')
            ->with('success', 'Venue created successfully.');
    }

   public function show(Venue $venue)
{
    $venue->load(['building', 'building.campus']);
    
    // Get upcoming events manually (since we don't have proper foreign key)
    $upcomingEvents = Event::where('venue', $venue->name)
        ->where('campus', $venue->building->campus->name)
        ->where('start_date', '>', now())  // This replaces ->upcoming()
        ->orderBy('start_date')
        ->get();
    
    // Also get all events for this venue for statistics
    $allEvents = Event::where('venue', $venue->name)
        ->where('campus', $venue->building->campus->name)
        ->get();
    
    return view('admin.venues.show', compact('venue', 'upcomingEvents', 'allEvents'));
}

    public function edit(Venue $venue)
    {
        $campuses = Campus::active()->get();
        $buildings = Building::active()->where('campus_id', $venue->building->campus_id)->get();
        $venueTypes = [
            'classroom' => 'Classroom',
            'auditorium' => 'Auditorium',
            'hall' => 'Hall',
            'lab' => 'Laboratory',
            'sports_complex' => 'Sports Complex',
            'conference_room' => 'Conference Room',
            'seminar_room' => 'Seminar Room',
            'open_space' => 'Open Space',
        ];
        
        return view('admin.venues.edit', compact('venue', 'campuses', 'buildings', 'venueTypes'));
    }

    public function update(Request $request, Venue $venue)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'building_id' => 'required|exists:buildings,id',
            'type' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'booking_fee' => 'nullable|numeric|min:0',
            'is_available' => 'boolean',
            'requires_approval' => 'boolean',
            'available_hours' => 'nullable|array',
        ]);

        if ($venue->name !== $validated['name']) {
            $slug = Str::slug($validated['name']);
            $counter = 1;
            while (Venue::where('slug', $slug)->where('id', '!=', $venue->id)->exists()) {
                $slug = Str::slug($validated['name']) . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        $venue->update($validated);

        return redirect()->route('admin.venues.index')
            ->with('success', 'Venue updated successfully.');
    }

    public function destroy(Venue $venue)
    {
        if ($venue->events()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete venue that has events. Delete events first.');
        }
        
        $venue->delete();
        
        return redirect()->route('admin.venues.index')
            ->with('success', 'Venue deleted successfully.');
    }

    public function toggleAvailability(Venue $venue)
    {
        $venue->update(['is_available' => !$venue->is_available]);
        
        return redirect()->back()
            ->with('success', 'Venue availability updated successfully.');
    }
}