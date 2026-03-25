<?php
// app/Http/Controllers/CampusController.php
namespace App\Http\Controllers;

use App\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CampusController extends Controller
{
    public function index(Request $request)
    {
        $query = Campus::query();
        
        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('location', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        $campuses = $query->withCount('buildings')->latest()->paginate(20);
        
        $activeCount = Campus::where('is_active', true)->count();
        $inactiveCount = Campus::where('is_active', false)->count();
        
        return view('admin.campuses.index', compact('campuses', 'activeCount', 'inactiveCount'));
    }

    public function create()
    {
        return view('admin.campuses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $slug = Str::slug($validated['name']);
        $counter = 1;
        while (Campus::where('slug', $slug)->exists()) {
            $slug = Str::slug($validated['name']) . '-' . $counter;
            $counter++;
        }
        $validated['slug'] = $slug;

        Campus::create($validated);

        return redirect()->route('admin.campuses.index')
            ->with('success', 'Campus created successfully.');
    }

    public function show(Campus $campus)
    {
        $campus->load('buildings');
        return view('admin.campuses.show', compact('campus'));
    }

    public function edit(Campus $campus)
    {
        return view('admin.campuses.edit', compact('campus'));
    }

    public function update(Request $request, Campus $campus)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        if ($campus->name !== $validated['name']) {
            $slug = Str::slug($validated['name']);
            $counter = 1;
            while (Campus::where('slug', $slug)->where('id', '!=', $campus->id)->exists()) {
                $slug = Str::slug($validated['name']) . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        $campus->update($validated);

        return redirect()->route('admin.campuses.index')
            ->with('success', 'Campus updated successfully.');
    }

    public function destroy(Campus $campus)
    {
        if ($campus->buildings()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete campus that has buildings. Delete buildings first.');
        }
        
        $campus->delete();
        
        return redirect()->route('admin.campuses.index')
            ->with('success', 'Campus deleted successfully.');
    }

    public function toggleActive(Campus $campus)
    {
        $campus->update(['is_active' => !$campus->is_active]);
        
        return redirect()->back()
            ->with('success', 'Campus status updated successfully.');
    }
}