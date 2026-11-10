<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }

    public function login(Request $request)
    {
        $data = $request->validate(['identifier' => 'required|string', 'password' => 'required|string']);
        $id = trim($data['identifier']);
        $field = filter_var($id, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        if ($field === 'phone') $id = preg_replace('/\s+/', '', $id);

        if (Auth::attempt([$field => $id, 'password' => $data['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(Auth::user()->is_admin ? route('admin.dashboard') : route('home'));
        }
        return back()->withErrors(['identifier' => 'Identifiants incorrects.'])->onlyInput('identifier');
    }

    public function showRegister() { return view('auth.register'); }

    public function register(Request $request)
    {
        $request->merge(['phone' => preg_replace('/\s+/', '', (string) $request->input('phone'))]);
        $data = $request->validate([
            'name'     => 'required|string|max:120',
            'phone'    => 'required|regex:/^\+?[0-9]{9,14}$/|unique:users,phone',
            'email'    => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user = User::create($data);
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('home')->with('success', 'Bienvenue chez The Cut Studio, ' . $user->name . ' !');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
