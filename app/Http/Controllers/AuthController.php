<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && !$user->isAccountActive()) {
            return back()->withErrors([
                'email' => 'Your account is inactive. Please contact an administrator to reactivate it.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if (!$user->isAccountActive()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Your account is inactive. Please contact an administrator to reactivate it.',
                ])->onlyInput('email');
            }
            
            // Load role if needed
            if (!$user->relationLoaded('role')) {
                $user->load('role');
            }
            
            // Check if user is admin by role slug
            $adminSlugs = ['super-admin', 'admin', 'administrator'];
            
            if ($user->role && in_array($user->role->slug, $adminSlugs)) {
                return redirect()->intended(route('dashboard'));
            }

            return redirect()->intended(route('events.guest.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $guestRole = Role::firstOrCreate(
            ['slug' => 'guest'],
            [
                'name' => 'Guest',
                'description' => 'Limited access user',
            ]
        );

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $guestRole->id,
            'status' => 'active',
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->route('events.guest.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
