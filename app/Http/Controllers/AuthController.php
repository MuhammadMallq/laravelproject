<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if (!$admin) {
            return back()->withErrors(['username' => 'Username tidak ditemukan!']);
        }

        // Plain text password comparison (as requested by user)
        if ($request->password !== $admin->password) {
            return back()->withErrors(['password' => 'Password salah!']);
        }

        session(['admin' => $admin->username]);

        return redirect()->route('admin.dashboard');
    }

    public function logout()
    {
        session()->forget('admin');
        return redirect()->route('home');
    }
}
