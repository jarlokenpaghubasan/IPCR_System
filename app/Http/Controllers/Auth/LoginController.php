<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Show login selection page
     */
    public function showLoginSelection()
    {
        return view('auth.login-selection');
    }

    /**
     * Show login form for specific role
     */
    public function showLoginForm($role)
    {
        $validRoles = ['admin', 'director', 'dean', 'faculty'];
        
        if (!in_array($role, $validRoles)) {
            return redirect()->route('login.selection')->with('error', 'Invalid role selected');
        }

        return view('auth.login', compact('role'));
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|in:admin,director,dean,faculty',
        ]);

        // Find user by username
        $user = User::where('username', $credentials['username'])->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'username' => 'Invalid username or password',
            ]);
        }

        // Check if user is active
        if (!$user->is_active) {
            return back()->withErrors([
                'username' => 'Your account is inactive',
            ]);
        }

        // Check if user has the selected role
        if (!$user->hasRole($credentials['role'])) {
            return back()->withErrors([
                'username' => 'You do not have the ' . ucfirst($credentials['role']) . ' role',
            ]);
        }

        // Log user in
        Auth::login($user);
        $request->session()->regenerate();

        // Redirect to appropriate dashboard
        return $this->redirectToDashboard($credentials['role']);
    }

    /**
     * Redirect to appropriate dashboard based on role
     */
    private function redirectToDashboard($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'director':
                return redirect()->route('director.dashboard');
            case 'dean':
                return redirect()->route('dean.dashboard');
            case 'faculty':
                return redirect()->route('faculty.dashboard');
            default:
                return redirect()->route('login.selection');
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login.selection')->with('success', 'Logged out successfully');
    }
}