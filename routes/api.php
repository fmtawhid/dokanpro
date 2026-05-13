<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\UserAuthenticationController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DraftController;
use App\Http\Controllers\Api\ExpensesController;
use App\Http\Controllers\Api\MasterController;
use App\Http\Controllers\Api\PosController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PurchageController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\SaleReturnController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\WareHouseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(PosController::class)->group(function () {
    Route::any('payment/razorpay/create-order', 'razorpayOrder');
});

Route::post('/sign-in', [UserAuthenticationController::class, 'signIn']);
Route::post('/forgot/password', [UserAuthenticationController::class, 'forgotPassword']);
Route::post('/check/otp', [UserAuthenticationController::class, 'checkOtp']);
Route::put('/reset/password', [UserAuthenticationController::class, 'resetPassword']);

Route::middleware('auth:api')->group(function () {

    Route::get('/logout', [UserAuthenticationController::class, 'logout']);
    Route::post('/apply/promo/code', [CouponController::class, 'applyPromoCode']);

    Route::get('/general-settings', [MasterController::class, 'index']);

    Route::any('invoice/{id?}', [PosController::class, 'invoice']);


    Route::controller(ProductController::class)->group(function () {
        Route::get('/products', 'index');
        Route::get('/product/search', 'search');
        Route::get('/category/product/{category}', 'categoryProduct');
        Route::get('/product/details/{product}', 'show');
        Route::delete('/product/delete/{product}', 'destroy');
        Route::post('/product/store', 'store');
        Route::get('/add/product/info', 'addProductInfo');
    });
    //Draft route
    Route::controller(DraftController::class)->group(function () {
        Route::get('/drafts', 'index');
        Route::get('/draft/delete/{sale}', 'delete');
    });
    //user profile
    Route::controller(UserController::class)->group(function () {
        Route::get('/profile', 'details');
        Route::post('/profile/update', 'profileUpdate');
        Route::put('/change/password', 'passwordChange');
    });
    //Purchase route
    Route::controller(PurchageController::class)->group(function () {
        Route::get('/purchase', 'index');
        Route::get('/purchase/pdf', 'purchasePdf');
    });
    //Sales route
    Route::controller(SaleController::class)->group(function () {
        Route::get('/sales', 'index');
        Route::get('/sale/pdf', 'salePdf');
        Route::get('sale/payment/success/{sale}', 'paymentSuccess');
        Route::get('sale/payment/failure/{sale}', 'paymentFailure');
    });
    //expense route
    Route::get('/expense', [ExpensesController::class, 'index']);
    //Report route
    Route::get('/reports', [ReportController::class, 'index']);
    //Accounting route
    Route::get('/admin/accounts', [AccountController::class, 'index']);

    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::controller(PosController::class)->group(function () {
        Route::get('/pos', 'pos');
        Route::get('/draft/details/{id}', 'details');
        Route::post('/pos/store', 'store');
        Route::get('payment-gateway-list', 'gatewayList');
        Route::post('redirect-payment', 'redirectPayment');
    });

    Route::controller(CustomerController::class)->group(function () {
        Route::get('/customer/search', 'search');
        Route::get('/customer/details/{customer}', 'details');
        Route::post('/customer/store', 'store');
        Route::get('/customer/groups', 'customerGroups');
        Route::get('/customer/groups', 'customerGroups');
    });

    Route::controller(WalletController::class)->group(function () {
        Route::get('/accounts', 'accounts');
        Route::get('/balance', 'balance');
        Route::post('/balance/transfer', 'balanceTransfer');
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories', 'index');
        Route::get('/categories/parent-category', 'parentCategory');
        Route::get('/categories/{category}', 'show');
        Route::post('/categories/store', 'store');
        Route::post('/categories/update/{category}', 'update')->name('categoryApi.update');
        Route::delete('/categories/delete/{category}', 'destroy');
    });

    Route::controller(BrandController::class)->group(function () {
        Route::get('/brands', 'index');
        Route::get('/brands/{brand}', 'show');
        Route::post('/brands/store', 'store');
        Route::post('/brands/update/{brand}', 'update')->name('brandApi.update');
        Route::delete('/brands/delete/{brand}', 'destroy');
    });

    Route::controller(WareHouseController::class)->group(function () {
        Route::get('/warehouses', 'index');
        Route::get('/warehouses/{warehouse}', 'show');
        Route::post('/warehouses/store', 'store');
        Route::put('/warehouses/update/{warehouse}', 'update');
        Route::delete('/warehouses/delete/{warehouse}', 'destroy');
    });
    //Sale Returns
    Route::controller(SaleReturnController::class)->group(function () {
        Route::post('sale-returns/search', 'search');
        Route::post('sale-return/product/store/{sale}', 'returnProduct');
    });


});
