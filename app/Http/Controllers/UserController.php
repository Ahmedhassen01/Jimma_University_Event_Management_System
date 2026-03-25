<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private function canManageActivation(): bool
    {
        $actor = auth()->user();
        if (!$actor) {
            return false;
        }

        $actor->loadMissing('role');
        $allowedSlugs = ['super-admin', 'super-administrator', 'administrator', 'admin'];

        return $actor->role && in_array($actor->role->slug, $allowedSlugs, true);
    }

    public function index()
    {
        $users = User::with('role')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'nullable|exists:roles,id',
            'status' => 'nullable|in:active,inactive,pending,suspended',
        ]);

        $status = $validated['status'] ?? 'active';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'status' => $status,
            'is_active' => $status === 'active',
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load(['role', 'role.permissions']);
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'nullable|exists:roles,id',
            'status' => 'nullable|in:active,inactive,pending,suspended',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role_id = $validated['role_id'];

        if (array_key_exists('status', $validated)) {
            $user->status = $validated['status'];
            $user->is_active = $validated['status'] === 'active';
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Prevent deactivating yourself from user-management.
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'You cannot deactivate your own account from user management.');
        }

        if (!$user->isAccountActive()) {
            return redirect()->route('users.index')->with('info', 'User account is already inactive.');
        }

        $user->forceFill([
            'status' => 'inactive',
            'is_active' => false,
            'remember_token' => null,
        ])->save();

        return redirect()->route('users.index')->with('success', 'User account deactivated successfully.');
    }

    public function reactivate(User $user)
    {
        if (!$this->canManageActivation()) {
            abort(403, 'Only administrators can reactivate accounts.');
        }

        $user->forceFill([
            'status' => 'active',
            'is_active' => true,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ])->save();

        return back()->with('success', "User account for {$user->name} has been reactivated.");
    }
}
