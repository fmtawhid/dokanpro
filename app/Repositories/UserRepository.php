<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserRepository extends Repository
{
    public static $path = "/users";
    public static function model()
    {
        return User::class;
    }

    public static function findByEmail($email)
    {
        return self::query()->where('email', $email)->first();
    }

    public static function getAccessToken(User $user)
    {
        $token = $user->createToken('user token');

        return [
            'auth_type' => 'Bearer',
            'token' => $token->accessToken,
            'expires_at' => $token->token->expires_at->format('Y-m-d H:i:s'),
        ];
    }

    //User create
    public static function storeByRequest($request)
    {
        $create = self::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'email_verified_at' => $request->email_verified_at,
            'company_name' => $request->company_name,
            'password' => bcrypt($request->password),
        ]);
        return $create;
    }
    // User UPdate
    public static function userUpdate(Request $request, User $user)
    {
        $userUpdate = self::update($user, [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone
        ]);

        $user->assignRole($request->role_name);

        if ($request->password) {
            self::update($user, [
                'password' => bcrypt($request->password),
            ]);
        }
        return $userUpdate;
    }

    //User Profile update
    public static function updateByRequest($request, User $user): User
    {
        $thumbnail = $user->thumbnail;
        if ($request->hasFile('image')) {
            $thumbnail = MediaRepository::updateOrCreateByRequest(
                $request->image,
                self::$path,
                'Image',
                $thumbnail,
            );
        }
        self::update($user, [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company_name' => $request->company_name,
            'thumbnail_id' => $thumbnail ? $thumbnail->id : null,
        ]);

        return $user;
    }

    public static function updateByPassword(ChangePasswordRequest $request, User $user): bool
    {
        return self::update($user, [
            'password' => bcrypt($request->password)
        ]);
    }

    public static function resetPassword(ResetPasswordRequest $request, User $user)
    {
        return self::update($user, [
            'password' => bcrypt($request->password)
        ]);
    }
    public static function emailVarifyAt(User $user)
    {
        return self::update($user, [
            'email_verified_at' => now()
        ]);
    }
}
