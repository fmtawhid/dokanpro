<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SigninRequest;;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SignInController extends Controller

{
    public function index()
    {
        
        return view('auth.login');
    }

    public function signin(SigninRequest $loginRequest)
    {
        $user = UserRepository::findByEmail($loginRequest->email);

        $credentials = $loginRequest->only('email', 'password');
        if (!$user) {
            return back()->with('error', 'Invalid email');
        }

        $shop = $user->shop;
        if (!$user->email_verified_at) {
            return back()->with('error', 'Please check your email and verify your email!');
        }
        if ($shop && $shop->status->value == 'Inactive') {
            return back()->with('error', 'Kindly get in touch with the administration as your shop is currently inactive!');
        }
        if (!Auth::attempt($credentials)) {
            return back()->with('error', 'Invalid password');
        }

        if (isset($user->roles[0]->name) && ($user->roles[0]->name == 'store' || $user->roles[0]->name == 'customer')) {
            return to_route('sale.pos');
        }
        if (isset($user->roles[0]->name) && $user->roles[0]->name == 'super admin') {
            return to_route('dashboard');
        }
        return to_route('root');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return to_route('signin.index')->with('success', 'You logout successfully');
    }

}
