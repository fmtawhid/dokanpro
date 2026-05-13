# Subscription Features System - Implementation Guide

## Overview
This system allows admins to create subscriptions with multiple features, users to purchase subscriptions and select desired features, and only enable purchased features for their shops.

## Database Structure

### Three New Tables Created:
1. **subscription_features** - Stores feature definitions (HRM, Accounting, Return, Expense, etc.)
2. **subscription_feature** - Pivot table linking subscriptions to features with pricing
3. **shop_subscription_features** - Tracks which features a shop has purchased

### New Migration:
- `add_selected_features_to_subscription_requests_table` - Stores user's feature selections

## Models & Relationships

### SubscriptionFeature Model
```php
$feature->subscriptions()      // Many-to-many with Subscription
$feature->shopSubscriptions()  // Many-to-many with ShopSubscription
```

### Subscription Model
```php
$subscription->features()      // Features included in this subscription with prices
```

### ShopSubscription Model
```php
$shopSubscription->features()  // Features purchased by this shop
```

## Admin Panel - Creating Subscriptions

### Step 1: Go to Subscriptions Management
- Navigate to Settings > Subscriptions
- The form now includes a "Features" section at the bottom

### Step 2: Select Features & Set Pricing
When creating or editing a subscription, you can:
- Check the features you want to include (HRM, Accounting, Return, Expense, etc.)
- Set individual pricing for each feature (in tk)
- Example:
  - HRM = 100 tk
  - Accounting = 75 tk
  - Return = 50 tk
  - Expense = 50 tk

### Step 3: Save the Subscription
The system will automatically link the selected features with their prices to the subscription.

## User Panel - Purchasing Subscriptions

### Step 1: Browse Available Subscriptions
- Go to Subscriptions > Purchase Subscription
- View all active subscriptions

### Step 2: Select Subscription & Features
- Click on a subscription to purchase
- You'll see all features included with their individual prices:
  ```
  [ ] HRM            ৳100
  [ ] Accounting     ৳75
  [ ] Return         ৳50
  [ ] Expense        ৳50
  ```
- Check the features you want to purchase
- **Total Price updates automatically** based on your selections

### Step 3: Choose Payment Method
- Select from available payment gateways (Stripe, Razorpay, PayStack, PayPal, PayFast, Orange Money)
- Proceed to payment

### Step 4: Payment Confirmation
- Payment is processed
- Selected features are automatically enabled for your shop
- Features are linked with expiration date matching subscription expiry

## Feature Enablement & Checking

### Using FeatureAccessTrait
Controllers can use the `FeatureAccessTrait` to check features:

```php
use App\Traits\FeatureAccessTrait;

class YourController extends Controller {
    use FeatureAccessTrait;
    
    public function someAction() {
        // Check if feature is enabled
        if (!$this->isFeatureEnabled('hrm')) {
            // Feature not available
        }
        
        // Get all enabled features
        $features = $this->getEnabledFeatures();
        
        // Require a feature (throws 403 if not available)
        $this->requireFeature('accounting');
    }
}
```

### Using SubscriptionFeatureRepository Directly
```php
use App\Repositories\SubscriptionFeatureRepository;

// Check if feature is enabled for a shop
$isEnabled = SubscriptionFeatureRepository::isFeatureEnabledForShop($shop, 'hrm');

// Get enabled features for a shop
$features = SubscriptionFeatureRepository::getEnabledFeaturesForShop($shop);
```

## Sidebar Integration

To show/hide menu items based on purchased features, update the sidebar.blade.php:

```blade
@if (auth()->user()->shop && $shop->currentSubscriptions()?->features->contains('slug', 'hrm'))
    <!-- Show HRM Menu Items -->
@endif

@if (auth()->user()->shop && $shop->currentSubscriptions()?->features->contains('slug', 'accounting'))
    <!-- Show Accounting Menu Items -->
@endif
```

## Feature Slugs
The system includes 4 default features (seeded automatically):
- `hrm` - Human Resource Management
- `accounting` - Accounting Module
- `return` - Return Management
- `expense` - Expense Management

To add new features, either:
1. Use the SubscriptionFeatureSeeder for default features
2. Manually create them in the subscription_features table
3. Create a new migration if needed

## Data Flow Summary

```
1. Admin creates Subscription
   ↓
2. Admin selects Features + Prices for that subscription
   ↓
3. User purchases subscription
   ↓
4. User selects which features to purchase
   ↓
5. Payment is processed with selected features
   ↓
6. ShopSubscription is created with expiration date
   ↓
7. Selected features are attached to ShopSubscription
   ↓
8. Menu items & features become available to the user
```

## Database Seeding

Run the SubscriptionFeatureSeeder to create default features:
```bash
php artisan db:seed --class=SubscriptionFeatureSeeder
```

Or in tinker:
```php
Artisan::call('db:seed', ['--class' => 'SubscriptionFeatureSeeder']);
```

## Important Files Modified

1. **Controllers:**
   - `app/Http/Controllers/SuperAdmin/SubscriptionController.php` - Added feature handling
   - `app/Http/Controllers/SuperAdmin/PaymentGatewayController.php` - Store selected features
   - `app/Http/Controllers/SubscriptionPurchaseController.php` - Pass features to view

2. **Models:**
   - `app/Models/Subscription.php` - Added relationships
   - `app/Models/ShopSubscription.php` - Added relationships
   - `app/Models/SubscriptionFeature.php` - New model
   - `app/Models/SubscriptionRequest.php` - Added selected_features cast

3. **Repositories:**
   - `app/Repositories/SubscriptionFeatureRepository.php` - New repository
   - `app/Repositories/SubscriptionRequestRepository.php` - Updated
   - `app/Repositories/ShopSubscriptionRepository.php` - May need updates for feature sync

4. **Views:**
   - `resources/views/subscription/index.blade.php` - Added features section
   - `resources/views/subscriptionPurchase/payment.blade.php` - Added feature selection with dynamic pricing

5. **Traits:**
   - `app/Traits/FeatureAccessTrait.php` - New trait for feature checking

6. **Migrations:**
   - `2024_04_01_000001_create_subscription_features_table.php`
   - `2024_04_01_000002_create_subscription_feature_pivot_table.php`
   - `2024_04_01_000003_create_shop_subscription_features_table.php`
   - `2024_04_01_000004_add_selected_features_to_subscription_requests_table.php`

## Next Steps

1. Run migrations:
   ```bash
   php artisan migrate
   ```

2. Seed default features:
   ```bash
   php artisan db:seed --class=SubscriptionFeatureSeeder
   ```

3. Create subscriptions with features in admin panel

4. Update sidebar/controllers to check feature availability using FeatureAccessTrait

5. Test purchasing a subscription with different feature combinations

## Testing Checklist

- [ ] Create subscription with multiple features and pricing
- [ ] View subscription in purchase page with correct feature list
- [ ] Select some features and verify total price updates dynamically
- [ ] Complete payment with selected features
- [ ] Verify shop_subscription_features table has entries
- [ ] Check that only purchased features are enabled
- [ ] Test feature access restrictions in controllers

