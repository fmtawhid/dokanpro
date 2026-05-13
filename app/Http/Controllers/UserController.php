<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UserProfileUpdateRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function profile()
    {
        return view('user.profile');
    }

    public function profileUpdate(UserProfileUpdateRequest $request, User $user)
    {
        try {
            // Allow profile image upload even in demo mode
            // Only block other fields in demo mode
            $isImageOnly = $request->hasFile('image') && 
                          !$request->has('name') && 
                          !$request->has('email') && 
                          !$request->has('phone');
            
            if (app()->environment('local') && !$isImageOnly) {
                return back()->with('error', 'This section is not available for demo version!');
            }
            
            UserRepository::updateByRequest($request, $user);
            return back()->with('success', 'Profile is updated successfully!');
        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    public function changePassword(ChangePasswordRequest $request, User $user)
    {
        if (app()->environment('local')) {
            return back()->with('error', 'This section is not available for demo version!');
        }
        $credentials = ['email' => auth()->user()->email, 'password' => $request->current_password];
        if (Auth::attempt($credentials)) {
            UserRepository::updateByPassword($request, $user);
            return back()->with('success', 'Password updated successfully!');
        }

        return back()->with("error", "Current Password doesn't match");
    }
}
