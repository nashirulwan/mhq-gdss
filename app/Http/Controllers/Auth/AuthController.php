<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Demo users array (temporary solution)
    private $demoUsers = [
        [
            'id' => 1,
            'name' => 'Admin SPK',
            'email' => 'admin@tahfidz.com',
            'password' => 'password123', // In production, this should be hashed
            'role' => 'admin'
        ],
        [
            'id' => 2,
            'name' => 'Ustadz Ahmad',
            'email' => 'juri@tahfidz.com',
            'password' => 'password123',
            'role' => 'juri'
        ],
        [
            'id' => 3,
            'name' => 'Peserta Test',
            'email' => 'peserta@tahfidz.com',
            'password' => 'password123',
            'role' => 'peserta'
        ]
    ];

    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check demo users
        $user = collect($this->demoUsers)->firstWhere('email', $credentials['email']);

        if ($user && $user['password'] === $credentials['password']) {
            // Store user in session
            session(['user' => $user]);

            if ($request->filled('remember')) {
                session()->put('remember_me', true);
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah. Silakan coba lagi.',
        ])->withInput($request->except('password'));
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        session()->forget('user');
        session()->forget('remember_me');

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}