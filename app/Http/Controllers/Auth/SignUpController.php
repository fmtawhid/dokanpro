<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\ShopCategoryRepository;
use App\Repositories\EmailVerificationRepository;
use App\Repositories\GeneralSettingRepository;
use App\Repositories\ShopRepository;
use App\Http\Requests\ShopOwnerRequest;
use App\Repositories\CurrencyRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class SignUpController extends Controller
{
    public function signup()
    {
        $shopCategories = ShopCategoryRepository::getAll();
        return view('auth.registration', compact('shopCategories'));
    }

    public function signupRequest(ShopOwnerRequest $request)
    {
        try {
            if (!config('mail.mailers.smtp.username') || !config('mail.mailers.smtp.password')) {
                return back()->with('error', 'Now you can not do signup because admin have not configured signup yet');
            }
            $user = UserRepository::storeByRequest($request);
            
            // Ensure user is saved and has ID
            if (!$user || !$user->id) {
                \Log::error('User creation failed - user object is invalid');
                throw new \Exception('Failed to create user account');
            }
            
            // Refresh user to ensure all attributes are loaded
            $user = $user->fresh();
            
            $shop = ShopRepository::storeByRequest($request, $user);
            $currency = CurrencyRepository::defaultCurrency($shop, $user);
            GeneralSettingRepository::storeByRequest($request, $shop, $currency);
            $user->shopUser()->attach($shop->id);
            
            // Assign admin role with error handling
            try {
                $user->assignRole('admin');
                \Log::info('Admin role assigned to user: ' . $user->email);
            } catch (\Exception $roleError) {
                \Log::error('Failed to assign admin role to user ' . $user->email . ': ' . $roleError->getMessage());
                throw new \Exception('Failed to assign admin role: ' . $roleError->getMessage());
            }
            
            EmailVerificationRepository::sendMailByUser($user);
            return to_route('signin.index')->with('success', 'Sign Up successfully done! Please check your email inbox or spam');
        } catch (\Exception $e) {
            \Log::error('SignUp error: ' . $e->getMessage() . ' | Stack: ' . $e->getTraceAsString());
            return back()->with('error', 'Sign up failed. Please try again or contact support.')->withInput();
        }
    }

    public function varification($token)
    {
        $varificationCode = EmailVerificationRepository::query()->where('token', $token)->first();
        if (!$varificationCode) {
            return to_route('signin.index')->with('error', 'This Email already varified!');
        }
        UserRepository::emailVarifyAt($varificationCode->user);
        $varificationCode->delete();
        return to_route('signin.index')->with('success', 'Email successfully varified! But wait for authorize confirmation');
    }
}
