<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function details()
    {
        $user = auth()->user();
        return $this->json('User details', [
            'user' => UserResource::make($user),
        ]);
    }

    public function profileUpdate(UserUpdateRequest $request)
    {
        $user = UserRepository::updateByRequest($request, auth()->user());
        $user->refresh();
        return $this->json('Profile updated successfully', [
            'user' => UserResource::make($user),
        ]);
    }

    public function passwordChange(ChangePasswordRequest $request)
    {
        $user = auth()->user();
        $credentials = ['email' => $user->email, 'password' => $request->current_password];
        if (Auth::attempt($credentials)) {
            return $this->json('Current password does not match', [], 422);
        }

        UserRepository::updateByPassword($request, $user);

        return $this->json('Password changed successfully', [
            'user' => UserResource::make($user),
        ]);
    }
}
