<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    private function normalizeProfilePhotoPath(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        $normalizedPath = ltrim(str_replace('\\', '/', $path), '/');

        if (Str::startsWith($normalizedPath, 'profile_photos/')) {
            return $normalizedPath;
        }

        return 'profile_photos/' . $normalizedPath;
    }

    /**
     * Constructor - ensure user is authenticated
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the user's profile
     */
    public function show()
    {
        $user = auth()->user()->load([
            'role', 
            'registrations' => function($query) {
                $query->with('event')
                      ->latest()
                      ->take(5);
            },
            'eventRequests' => function($query) {
                $query->latest()
                      ->take(5);
            }
        ]);
        
        // Calculate statistics
        $stats = [
            'total_events' => $user->registrations()->count(),
            'upcoming_events' => $user->registrations()
                ->whereHas('event', function($q) {
                    $q->where('start_date', '>', now());
                })->count(),
            'pending_requests' => $user->eventRequests()
                ->where('status', 'pending')
                ->count(),
            'notifications' => $user->unread_notifications_count ?? 0,
        ];
        
        return view('profile.show', compact('user', 'stats'));
    }

    /**
     * Show the form for editing the profile
     */
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Serve profile photo file directly from storage.
     */
    public function photo(User $user)
    {
        if (!$user->profile_photo) {
            abort(404);
        }

        $photoPath = $this->normalizeProfilePhotoPath($user->profile_photo);

        if (!$photoPath || !Storage::disk('public')->exists($photoPath)) {
            abort(404);
        }

        return Storage::disk('public')->response($photoPath);
    }

    /**
     * Update the user's profile
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        
        // Validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'office_address' => ['nullable', 'string', 'max:500'],
            'expertise' => ['nullable', 'string', 'in:student,employee,faculty,staff,alumni,guest,other'],
            'department' => ['nullable', 'string', 'max:255'],
            'faculty' => ['nullable', 'string', 'max:255'],
            'student_id' => ['nullable', 'string', 'max:50'],
            'employee_id' => ['nullable', 'string', 'max:50'],
            'year_of_study' => ['nullable', 'string', 'in:1,2,3,4,5,graduate'],
            'position' => ['nullable', 'string', 'max:255'],
            'email_notifications' => ['nullable', 'boolean'],
            'newsletter_subscription' => ['nullable', 'boolean'],
            'event_reminders' => ['nullable', 'boolean'],
            'theme_preference' => ['nullable', 'string', 'in:light,dark,system'],
            'language' => ['nullable', 'string', 'in:en,am'],
        ];

        // Add conditional validation
        if ($request->expertise === 'student') {
            $rules['student_id'] = ['required', 'string', 'max:50'];
            $rules['year_of_study'] = ['required', 'string', 'in:1,2,3,4,5,graduate'];
        }

        if (in_array($request->expertise, ['employee', 'faculty', 'staff'])) {
            $rules['employee_id'] = ['required', 'string', 'max:50'];
            $rules['position'] = ['required', 'string', 'max:255'];
        }

        $validated = $request->validate($rules);

        // Handle boolean fields (checkboxes)
        $validated['email_notifications'] = $request->has('email_notifications');
        $validated['newsletter_subscription'] = $request->has('newsletter_subscription');
        $validated['event_reminders'] = $request->has('event_reminders');

        // Update user
        $user->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Update profile photo
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        // Delete old photo if exists
        if ($user->profile_photo) {
            $oldPhotoPath = $this->normalizeProfilePhotoPath($user->profile_photo);
            if ($oldPhotoPath) {
                Storage::disk('public')->delete($oldPhotoPath);
            }
        }

        // Store new photo
        $fileName = time() . '_' . uniqid() . '.' . $request->profile_photo->extension();
        $request->profile_photo->storeAs('profile_photos', $fileName, 'public');

        // Update user
        $user->profile_photo = $fileName;
        $user->save();

        return redirect()->route('profile.edit')
            ->with('success', 'Profile photo updated successfully!');
    }

    /**
     * Delete profile photo
     */
    public function destroyPhoto(Request $request)
    {
        $user = auth()->user();

        if ($user->profile_photo) {
            $photoPath = $this->normalizeProfilePhotoPath($user->profile_photo);
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            $user->profile_photo = null;
            $user->save();
        }

        return redirect()->route('profile.edit')
            ->with('success', 'Profile photo removed successfully.');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()],
            'new_password_confirmation' => ['required'],
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile.show')
            ->with('success', 'Password changed successfully!');
    }

    /**
     * Deactivate user account without deleting related data
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = auth()->user();
        
        // Deactivate account instead of deleting data.
        $user->forceFill([
            'status' => 'inactive',
            'is_active' => false,
            'remember_token' => null,
        ])->save();

        // Logout user
        auth()->logout();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Your account has been deactivated successfully. Contact an administrator to reactivate it.');
    }
}
