<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Session;

class AuthManager extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect(route("home"));
        }
        return view('login');
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended(route('home'));
        }

        return redirect(route('login'))->with('error', 'Credentials is not valid');
    }

    public function registration()
    {
        if (Auth::check()) {
            return redirect(route("home"));
        }
        return view('registration');
    }

    public function registrationPost(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);

        if (!$user) {
            return redirect(route('registration'))->with('error', 'Registration failed');
        }

        return redirect(route('login'))->with('success', 'Registration successfull');
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect(route('login'))->with('success', 'Succesfully logged out');
    }

    public function profile()
    {
        return view('profile');
    }
}
