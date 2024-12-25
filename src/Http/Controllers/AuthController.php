<?php 
namespace Magicbox\LaraQuickKit\Http\Controllers;

use Magicbox\LaraQuickKit\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Magicbox\LaraQuickKit\Http\Requests\LoginRequest;
use Magicbox\LaraquickKit\Http\Requests\RegisterRequest;


class AuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('laraquick::auth.login');
    }

    // Handle login
    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('home');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // Show register form
    public function showRegisterForm()
    {
        return view('laraquick::auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }
}