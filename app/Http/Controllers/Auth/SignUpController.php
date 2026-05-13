<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\ShopCategoryRepository;
use App\Repositories\GeneralSettingRepository;
use App\Repositories\ShopRepository;
use App\Http\Requests\ShopOwnerRequest;
use App\Repositories\CurrencyRepository;
use App\Repositories\UserRepository;
use App\Repositories\SubscriptionRepository;
use App\Repositories\ShopSubscriptionRepository;
use App\Enums\IsHas;
use App\Enums\PaymentStatus;
use App\Enums\PaymentGateway;
use App\Enums\SubscriptionApprovalStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
            
            // Auto-verify email for immediate login
            $user->update(['email_verified_at' => now()]);
            
            $shop = ShopRepository::storeByRequest($request, $user);
            
            // Set shop status to Active for immediate access
            $shop->update(['status' => 'Active']);
            
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
            
            // Create free trial subscription for first package
            $this->createFreeTrialSubscription($shop);
            
            return to_route('signin.index')->with('success', 'Sign Up successfully done! You can now login to your account');
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

    private function createFreeTrialSubscription($shop)
    {
        try {
            // Get first subscription package (ID 1)
            $subscription = SubscriptionRepository::find(1);
            
            if (!$subscription) {
                \Log::warning('Free trial subscription package not found for shop: ' . $shop->id);
                return;
            }

            // Calculate expiry date based on recurring type
            $date = now();
            if ($subscription->recurring_type->value == 'Onetime') {
                $expiredAt = now();
            } elseif ($subscription->recurring_type->value == 'Weekly') {
                $expiredAt = Carbon::parse($date)->addDays(7);
            } elseif ($subscription->recurring_type->value == 'Monthly') {
                $expiredAt = Carbon::parse($date)->addMonths(1);
            } elseif ($subscription->recurring_type->value == 'Yearly') {
                $expiredAt = Carbon::parse($date)->addYears(1);
            } else {
                $expiredAt = Carbon::parse($date)->addMonths(1);
            }

            // Create shop subscription (free trial - already approved)
            $shopSubscription = \App\Models\ShopSubscription::create([
                'shop_id' => $shop->id,
                'subscription_id' => $subscription->id,
                'is_current' => IsHas::YES->value,
                'payment_status' => PaymentStatus::PAID->value,
                'payment_gateway' => PaymentGateway::TRIAL->value,
                'expired_at' => $expiredAt,
                'status' => SubscriptionApprovalStatus::APPROVED->value
            ]);

            // Attach all features from this subscription package
            $features = $subscription->features()->get();
            if ($features->isNotEmpty()) {
                $featuresData = [];
                foreach ($features as $feature) {
                    $featuresData[$feature->id] = [
                        'price' => $feature->pivot->price ?? 0,
                        'expired_at' => $expiredAt,
                    ];
                }
                $shopSubscription->features()->attach($featuresData);
            }

            \Log::info('Free trial subscription created for shop: ' . $shop->id);
        } catch (\Exception $e) {
            \Log::error('Failed to create free trial subscription for shop ' . $shop->id . ': ' . $e->getMessage());
        }
    }
}
