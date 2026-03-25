<?php
// app/Http/Controllers/BuildingController.php
namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BuildingController extends Controller
{
    public function index(Request $request)
    {
        $query = Building::query()->with('campus');
        
        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }
        
        // Filter by campus
        if ($request->filled('campus_id')) {
            $query->where('campus_id', $request->campus_id);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        $buildings = $query->withCount('venues')->latest()->paginate(20);
        $campuses = Campus::active()->get();
        
        return view('admin.buildings.index', compact('buildings', 'campuses'));
    }

    public function create()
    {
        $campuses = Campus::active()->get();
        return view('admin.buildings.create', compact('campuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'campus_id' => 'required|exists:campuses,id',
            'code' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'floors' => 'nullable|integer|min:1',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $slug = Str::slug($validated['name']);
        $counter = 1;
        while (Building::where('slug', $slug)->exists()) {
            $slug = Str::slug($validated['name']) . '-' . $counter;
            $counter++;
        }
        $validated['slug'] = $slug;

        Building::create($validated);

        return redirect()->route('admin.buildings.index')
            ->with('success', 'Building created successfully.');
    }

    public function show(Building $building)
    {
        $building->load(['campus', 'venues']);
        return view('admin.buildings.show', compact('building'));
    }

    public function edit(Building $building)
    {
        $campuses = Campus::active()->get();
        return view('admin.buildings.edit', compact('building', 'campuses'));
    }

    public function update(Request $request, Building $building)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'campus_id' => 'required|exists:campuses,id',
            'code' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'floors' => 'nullable|integer|min:1',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        if ($building->name !== $validated['name']) {
            $slug = Str::slug($validated['name']);
            $counter = 1;
            while (Building::where('slug', $slug)->where('id', '!=', $building->id)->exists()) {
                $slug = Str::slug($validated['name']) . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        $building->update($validated);

        return redirect()->route('admin.buildings.index')
            ->with('success', 'Building updated successfully.');
    }

    public function destroy(Building $building)
    {
        if ($building->venues()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete building that has venues. Delete venues first.');
        }
        
        $building->delete();
        
        return redirect()->route('admin.buildings.index')
            ->with('success', 'Building deleted successfully.');
    }

    public function toggleActive(Building $building)
    {
        $building->update(['is_active' => !$building->is_active]);
        
        return redirect()->back()
            ->with('success', 'Building status updated successfully.');
        }


        public function getBuildingsByCampus($campusId)
{
    $buildings = Building::where('campus_id', $campusId)
        ->where('is_active', true)
        ->get(['id', 'name']);
    
    return response()->json($buildings);
}


}


