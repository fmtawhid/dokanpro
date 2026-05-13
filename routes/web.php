<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Api\PosController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\StockCountController;
use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\ShopCategoryController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\ManualInstallController;
use App\Http\Controllers\MoneyTransferController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ShopPaymentGatewayController;
use App\Http\Controllers\SubscriptionPurchaseController;
use App\Http\Controllers\SuperAdmin\SubscriptionController;
use App\Http\Controllers\SuperAdmin\LandingInstallController;
use App\Http\Controllers\SuperAdmin\PaymentGatewayController;
use App\Http\Controllers\SuperAdmin\DashBoardController as SuperAdminDashBoardController;


use App\Http\Controllers\SuperAdmin\UserRoleController;
use App\Http\Controllers\SuperAdmin\PermissionController;
use App\Http\Controllers\SuperAdmin\RoleController as SuperAdminRoleController;




Route::controller(PosController::class)->group(function () {
    Route::any('payment-success', 'successPayment')->name('payment.success');
    Route::any('payment/cancel', 'cancelPayment')->name('payment.cancel');
    Route::any('razorpay', 'razorpay')->name('razorpay');
    Route::any('/order-payment/success/{user_id?}/{id?}', 'success')->name('order-payment.success');
});

// Auth
Route::controller(SignInController::class)
    ->middleware('guest')
    ->group(function () {
        Route::get('/signin', 'index')->name('signin.index');
        Route::post('/signin', 'signin')->name('signin.request');
    });
Route::controller(SignUpController::class)
    ->middleware('guest')
    ->group(function () {
        Route::get('/signup', 'signup')->name('signup.index');
        Route::post('/signup/request', 'signupRequest')->name('signup.request');
        Route::get('/signup/varification/{token}', 'varification')->name('signup.varification');
    });
Route::middleware(['auth', 'check_permission'])->group(function () {
    Route::get('/signout', [SigninController::class, 'logout'])
        ->middleware('auth')
        ->name('signout');

    Route::get('/', [DashboardController::class, 'index'])->name('root');

    Route::get('/dashboard', [SuperAdminDashBoardController::class, 'dashboard'])->name('dashboard');

    Route::get('/landing/install', [LandingInstallController::class, 'index'])->name('landing.install');
    Route::post('/landing/store', [LandingInstallController::class, 'store'])->name('landing.store');


    //subscriptions
    Route::controller(SubscriptionController::class)->group(function () {
        Route::get('subscriptions', 'index')->name('subscription.index');
        Route::get('subscription/report', 'report')->name('subscription.report');
        Route::get('subscription/pending-approvals', 'pendingApprovals')->name('subscription.pending.approvals');
        Route::post('subscription/{shopSubscription}/approve', 'approve')->name('subscription.approve');
        Route::post('subscription/{shopSubscription}/reject', 'reject')->name('subscription.reject');
        Route::get('subscription/status-chanage/{subscription}/{status}', 'statusChanage')->name('subscription.status.chanage');
        Route::post('subscription/store', 'store')->name('subscription.store');
        Route::put('subscription/update/{subscription}', 'update')->name('subscription.update');
    });
    // Unit
    Route::controller(SubscriptionPurchaseController::class)->group(function () {
        Route::get('subscription-purchase', 'index')->name('subscription.purchase.index');
        Route::get('subscription-purchase/update/{subscription}', 'update')->name('subscription.purchase.update');
    });
    // Payment Gateway
    Route::controller(PaymentGatewayController::class)->group(function () {
        Route::get('/payment-gateway', 'index')->name('payment-gateway.index');
        Route::post('/payment-gateway/{paymentGateway}/update', 'update')->name('payment-gateway.update');
        Route::get('/payment-gateway/{paymentGateway}/toggle', 'toggle')->name('payment-gateway.toggle');
        Route::post('payment/process/{subscriptionRequest}', 'process')->name('payment.process');
        Route::post('orange/money/payment/process/{subscriptionRequest}', 'orangeMoneyPaymentprocess')->name('orange.money.payment.process');
        Route::post('/payment/initiate', 'initiatePayment')->name('payment.initiate');
        Route::post('/payment/notify', 'paymentNotify')->name('payment.notify');
        Route::post('/payment/callback', 'paymentNotify')->name('payment.callback');
        Route::get('/payment/cancel', 'paymentCancel')->name('payment.cancel');
    });

    //Role Permissions
    Route::controller(RoleController::class)->group(function () {
        Route::get('role', 'index')->name('role.index');
        Route::post('role/store', 'store')->name('role.store');
        Route::put('role/update/{role}', 'update')->name('role.update');
        Route::get('role/permission/{id}', 'permission')->name('role.permission');
        Route::post('role/set-permission/{role}', 'setPermission')->name('role.set.permission');
    });
    // Unit
    Route::controller(UnitController::class)->group(function () {
        Route::get('unit', 'index')->name('unit.index');
        Route::post('unit/store', 'store')->name('unit.store');
        Route::post('unit/update/{unit}', 'update')->name('unit.update');
        Route::get('unit/delete/{unit}', 'delete')->name('unit.delete');
    });
    //Category
    Route::controller(CategoryController::class)->group(function () {
        Route::get('categories', 'index')->name('category.index');
        Route::post('category/store', 'store')->name('category.store');
        Route::put('category/update/{category}', 'update')->name('category.update');
        Route::get('category/delete/{category}', 'delete')->name('category.delete');
        Route::get('category/trash', 'trash')->name('category.trash');
        Route::get('category/force-delete/{id}', 'forceDelete')->name('category.forceDelete');
        Route::get('category/restore/{id}', 'restore')->name('category.restore');
        Route::post('category/import', 'import')->name('category.import');
        Route::get('category/print', 'categoryPrint')->name('category.print');
        Route::get('download/sample', 'downloadSample')->name('download.category.sample');
    });
    //Brand
    Route::controller(BrandController::class)->group(function () {
        Route::get('brand', 'index')->name('brand.index');
        Route::post('brand/store', 'store')->name('brand.store');
        Route::put('brand/update/{brand}', 'update')->name('brand.update');
        Route::get('brand/delete/{brand}', 'delete')->name('brand.delete');
        Route::get('brand/trash', 'trash')->name('brand.trash');
        Route::get('brand/restore/{id}', 'restore')->name('brand.restore');
        Route::get('brand/force-delete/{id}', 'forceDelete')->name('brand.forceDelete');
    });

    //Supplier
    Route::controller(SupplierController::class)->group(function () {
        Route::get('supplier', 'index')->name('supplier.index');
        Route::get('supplier/create', 'create')->name('supplier.create');
        Route::post('supplier/store', 'store')->name('supplier.store');
        Route::get('supplier/edit/{supplier}', 'edit')->name('supplier.edit');
        Route::put('supplier/update/{supplier}', 'update')->name('supplier.update');
        Route::get('supplier/destroy/{supplier}', 'destroy')->name('supplier.destroy');
        Route::get('supplier/trash', 'trash')->name('supplier.trash');
        Route::get('supplier/restore/{id}', 'restore')->name('supplier.restore');
        Route::get('supplier/force-delete/{id}', 'forceDelete')->name('supplier.forceDelete');
        Route::post('supplier/import', 'import')->name('supplier.import');
        Route::get('download/supplier/sample', 'downloadSupplierSample')->name('download.supplier.sample');
    });
    //warehouse
    Route::controller(WarehouseController::class)->group(function () {
        Route::get('warehouse', 'index')->name('warehouse.index');
        Route::post('warehouse/store', 'store')->name('warehouse.store');
        Route::post('warehouse/update/{warehouse}', 'update')->name('warehouse.update');
        Route::get('warehouse/delete/{warehouse}', 'delete')->name('warehouse.delete');
        Route::get('warehouse/trash', 'trash')->name('warehouse.trash');
        Route::get('warehouse/restore/{id}', 'restore')->name('warehouse.restore');
        Route::get('warehouse/force-delete/{id}', 'forceDelete')->name('warehouse.forceDelete');
    });
    //Tax
    Route::controller(TaxController::class)->group(function () {
        Route::get('tax', 'index')->name('tax.index');
        Route::post('tax/store', 'store')->name('tax.store');
        Route::post('tax/update/{tax}', 'update')->name('tax.update');
        Route::get('tax/delete/{tax}', 'delete')->name('tax.delete');
    });

    // Shop Payment Gateway
    Route::controller(ShopPaymentGatewayController::class)->group(function () {
        Route::get('shop-payment-gateway', 'index')->name('shop-payment-gateway.index');
        Route::post('shop-payment-gateway/{paymentGateway}/update', 'update')->name('shop-payment-gateway.update');
        Route::get('shop-payment-gateway/{paymentGateway?}/toggle', 'toggle')->name('shop-payment-gateway.toggle');
        Route::post('shop-payment/process/{subscriptionRequest}', 'process')->name('shop-payment.process');
    });
    //Product
    Route::controller(ProductController::class)
        ->middleware('subscriptionExpireCheck')
        ->group(function () {
            Route::get('products', 'index')->name('product.index');
            Route::get('product/create', 'create')->name('product.create');
            Route::post('product/store', 'store')->name('product.store');
            Route::get('product/edit/{product}', 'edit')->name('product.edit');
            Route::put('product/update/{product}', 'update')->name('product.update');
            Route::get('product/destroy/{product}', 'destroy')->name('product.destroy');
            Route::get('product/trash', 'trash')->name('product.trash');
            Route::get('product/force-delete/{id}', 'forceDelete')->name('product.forceDelete');
            Route::get('product/restore/{id}', 'restoreProduct')->name('product.restore');
            Route::get('product/gencode', 'generateCode')->name('product.generate.code');
            Route::get('product/search', 'productSearch')->name('product.search');
            Route::get('product/details', 'productDetails')->name('product.details');
            Route::post('product/barcode/generate', 'barcodeGenerate')->name('product.barcode.generate');
            Route::get('barcode-print', 'printBarcode')->name('barcode.print');
            Route::get('products/saleunit/', 'saleUnit')->name('product.sale.unit');
            Route::post('product/import', 'import')->name('product.import');
            Route::get('product/sample/download', 'productDownloadSample')->name('download.product.sample');
        });
    //Customer
    Route::controller(CustomerController::class)->group(function () {
        Route::get('customer', 'index')->name('customer.index');
        Route::get('customer/create', 'create')->name('customer.create');
        Route::post('customer/store', 'store')->name('customer.store');
        Route::get('customer/edit/{customer}', 'edit')->name('customer.edit');
        Route::put('customer/update/{customer}', 'update')->name('customer.update');
        Route::get('customer/destroy/{customer}', 'destroy')->name('customer.destroy');
        Route::get('customer/trash', 'trash')->name('customer.trash');
        Route::get('customer/restore/{id}', 'restore')->name('customer.restore');
        Route::get('customer/force-delete/{id}', 'forceDelete')->name('customer.forceDelete');
        Route::post('customer/import', 'import')->name('customer.import');
        Route::get('customer/search', 'search')->name('customer.search');
        Route::get('download/customer/sample', 'downloadCustomerSample')->name('download.customer.sample');
        Route::get('customer/add', 'customerAdd')->name('customer.add');
    });
    // Purchase
    Route::controller(PurchaseController::class)
        ->middleware('subscriptionExpireCheck')
        ->group(function () {
            Route::get('purchase/list', 'index')->name('purchase.index');
            Route::get('purchase/create', 'create')->name('purchase.create');
            Route::post('purchase/store', 'store')->name('purchase.store');
            Route::get('purchase/edit/{purchase}', 'edit')->name('purchase.edit');
            Route::put('purchase/update/{purchase}', 'update')->name('purchase.update');
            Route::get('purchase/destroy/{purchase}', 'destroy')->name('purchase.destroy');
            Route::post('purchase/add_payment/{id}', 'addPayment')->name('purchase.add.payment');
            Route::get('purchase/deletepayment/{payment}', 'deletePayment')->name('purchase.delete.payment');
            Route::get('purchase/batch', 'batch')->name('purchase.batch');
        });
    //User
    Route::controller(UserController::class)->group(function () {
        Route::get('user/profile', 'profile')->name('profile.index');
        Route::put('user/update-profile/{user}', 'profileUpdate')->name('profile.update');
        Route::post('user/changepass/{user}', 'changePassword')->name('user.password');
    });
    //customer group
    Route::controller(CustomerGroupController::class)->group(function () {
        Route::get('customer-group', 'index')->name('customer.group.index');
        Route::post('customer-group/store', 'store')->name('customer.group.store');
        Route::put('customer-group/update/{customerGroup}', 'update')->name('customer.group.update');
        Route::get('customer-group/delete/{customerGroup}', 'delete')->name('customer.group.delete');
        Route::get('customer-group/trash', 'trash')->name('customer.group.trash');
        Route::get('customer-group/restore/{id}', 'restore')->name('customer.group.restore');
        Route::get('customer-group/force-delete/{id}', 'forceDelete')->name('customer.group.forceDelete');
    });
    //Sales
    Route::controller(SaleController::class)
        ->middleware('subscriptionExpireCheck')
        ->group(function () {
            Route::get('sales', 'index')->name('sale.index');
            Route::get('sales/draft', 'draft')->name('sale.draft');
            Route::get('sales/draft/delete/{sale}', 'draftDelete')->name('sale.draft.delete');
            Route::get('pos', 'posSale')->name('sale.pos')->middleware('subscriptionExpireCheck');
            Route::get('sales/invoice/{id}', 'generateInvoice')->name('sale.invoice.generate');
            Route::get('pos/data', 'posData')->name('sale.pos.data');
            Route::get('orange-pay', 'orangeMoneyPaymentprocess')->name('sale.orange-pay');
            Route::get('sale/pos', 'sale')->name('sale.pos.store');
        });
    //Stock Count
    Route::controller(StockCountController::class)->group(function () {
        Route::get('stock/count', 'index')->name('stock.count.index');
        Route::post('stock/count/store', 'store')->name('stock.count.store');
    });
    //Report
    Route::controller(ReportController::class)
        ->middleware('subscriptionExpireCheck')
        ->group(function () {
            Route::get('report/summary', 'summary')->name('report.summary');
            Route::get('report/profit-loss', 'profitLoss')->name('report.profit.loss');
            Route::get('report/sales', 'sales')->name('report.sales');
            Route::get('report/purchases', 'purchases')->name('report.purchases');
            Route::get('purchases/print', 'purchasesPrint')->name('purchases.print');
            Route::get('sale/print', 'salePrint')->name('sale.print');
            Route::get('report/product', 'product')->name('report.product');
            Route::get('product/print', 'productPrint')->name('product.print');
            Route::get('report/expense', 'expense')->name('report.expense');
            Route::get('expense/product', 'expensePrint')->name('expense.print');
        });
    //Sale Returns
    Route::controller(SaleReturnController::class)
        ->middleware('subscriptionExpireCheck')
        ->group(function () {
            Route::get('sale-returns', 'index')->name('sale.return.index');
            Route::post('sale-returns/search', 'search')->name('sale.return.search');
            Route::get('sale-returns/details/{sale}', 'details')->name('sale.return.details');
            Route::post('sale-return/product/store/{sale}', 'returnProduct')->name('sale.return.product.store');
        });
    //General Setting
    Route::controller(SettingsController::class)->group(function () {
        Route::get('settings/general-settings', 'generalSettings')->name('settings.general');
        Route::post('settings/general-settings-store/{generalSetting}', 'store')->name('settings.general.store');
        Route::post('settings/mail-settings-store', 'mailSettingsStore')->name('settings.mail.store');
        Route::get('settings/theme-settings/{mode}', 'themeSettings')->name('settings.theme');
        Route::get('settings/mail-settings', 'mailSettings')->name('settings.mail');
        Route::get('settings/business-modules', 'businessModules')->name('settings.business.modules');
        Route::post('settings/business-modules/update', 'businessModulesUpdate')->name('settings.business.modules.update');
        Route::get('settings/database-backup', 'databaseBackup')->name('settings.database.backup');
    });
    //Expense Category
    Route::controller(ExpenseCategoryController::class)->group(function () {
        Route::get('expense-categories', 'index')->name('expenseCategory.index');
        Route::post('expense-categories/store', 'store')->name('expenseCategory.store');
        Route::post('expense-categories/update/{expenseCategory}', 'update')->name('expenseCategory.update');
        Route::get('expense-categories/destroy/{expenseCategory}', 'destroy')->name('expenseCategory.destroy');
        Route::get('expense-categories/gencode', 'generateCode')->name('expenseCategory.code');
    });
    //Expense
    Route::controller(ExpenseController::class)
        ->middleware('subscriptionExpireCheck')
        ->group(function () {
            Route::get('expense', 'index')->name('expense.index');
            Route::post('expense/store', 'store')->name('expense.store');
            Route::put('expense/update/{expense}', 'update')->name('expense.update');
            Route::get('expense/destroy/{expense}', 'destroy')->name('expense.destroy');
        });
    //Coupon
    Route::controller(CouponController::class)
        ->middleware('subscriptionExpireCheck')
        ->group(function () {
            Route::get('coupons', 'index')->name('coupon.index');
            Route::post('coupon/store', 'store')->name('coupon.store');
            Route::put('coupon/update/{coupon}', 'update')->name('coupon.update');
            Route::get('coupon/destroy/{coupon}', 'destroy')->name('coupon.destroy');
            Route::get('coupon/apply', 'applyPromoCode')->name('coupon.apply');
        });

    //accounting
    Route::controller(AccountsController::class)
        ->middleware('subscriptionExpireCheck')
        ->group(function () {
            Route::get('accounts', 'index')->name('account.index');
            Route::post('account/store', 'store')->name('account.store');
            Route::put('account/update/{account}', 'update')->name('account.update');
            Route::put('account/balance/{account}', 'updateBalance')->name('account.update.balance');
            Route::get('account/destroy/{account}', 'destroy')->name('account.destroy');
            Route::get('account/balancesheet', 'balanceSheet')->name('account.balancesheet');
        });
    //Money Transfer
    Route::controller(MoneyTransferController::class)
        ->middleware('subscriptionExpireCheck')
        ->group(function () {
            Route::get('money-transfers', 'index')->name('money.transfer.index');
            Route::post('money-transfers/store', 'store')->name('money.transfer.store');
            Route::post('money-transfers/update/{moneyTransfer}', 'update')->name('money.transfer.update');
            Route::get('money-transfers/destroy/{moneyTransfer}', 'destroy')->name('money.transfer.destroy');
        });
    //Currency
    Route::controller(CurrencyController::class)->group(function () {
        Route::get('currency', 'index')->name('currency.index');
        Route::post('currency/store', 'store')->name('currency.store');
        Route::post('currency/update/{currency}', 'update')->name('currency.update');
        Route::get('currency/delete/{currency}', 'delete')->name('currency.delete');
    });
    // Language
    Route::controller(LanguageController::class)->group(function () {
        Route::get('language', 'index')->name('language.index');
        Route::get('language/create', 'create')->name('language.create');
        Route::post('language/store', 'store')->name('language.store');
        Route::get('language/{language}/edit', 'edit')->name('language.edit');
        Route::put('language/{language}/update', 'update')->name('language.update');
        Route::get('language/{language}/delete', 'delete')->name('language.delete');
    });
    //Shop Category
    Route::controller(ShopCategoryController::class)->group(function () {
        Route::get('shop-categories', 'index')->name('shop.category.index');
        Route::post('shop-category/store', 'store')->name('shop.category.store');
        Route::put('shop-category/update/{shopCategory}', 'update')->name('shop.category.update');
        Route::get('shop-category/delete/{shopCategory}', 'delete')->name('shop.category.delete');
        Route::get('shop-category/status-chanage/{shopCategory}/{status}', 'statusChanage')->name('shop.category.status.chanage');
        Route::post('shop-category/business-module/update/{shopCategory}', 'businessModuleUpdate')->name('shop.category.business.module.update');
    });
    //Shop
    Route::controller(ShopController::class)->group(function () {
        Route::get('shops', 'index')->name('shop.index');
        Route::get('shop/create', 'create')->name('shop.create');
        Route::post('shop/store', 'store')->name('shop.store');
        Route::put('shop/update/{shop}', 'update')->name('shop.update');
        Route::get('shop/delete/{shop}', 'delete')->name('shop.delete');
        Route::get('shop/status-chanage/{shop}/{status}', 'statusChanage')->name('shop.status.chanage');
        Route::put('shop/owner/reset/password/{user}', 'resetPassword')->name('shop.owner.reset.password');
        Route::get('shop/life-time-expire/{shop}', 'lifeTimeExpire')->name('shop.life.time.expire.chanage');
        Route::post('shop/subscription-chanage/{shop}', 'subscriptionChanage')->name('shop.subscription.chanage');
    });

    // Store
    Route::controller(StoreController::class)
        ->middleware('subscriptionExpireCheck')
        ->group(function () {
            Route::get('/stores', 'index')->name('store.index');
            Route::get('/stores/create', 'create')->name('store.create');
            Route::post('/stores/store', 'store')->name('store.store');
            Route::get('/stores/edit/{store}', 'edit')->name('store.edit');
            Route::put('/stores/update/{store}', 'update')->name('store.update');
            Route::get('/stores/delete/{store}', 'delete')->name('store.delete');
        });
    // Department
    Route::controller(DepartmentController::class)->group(function () {
        Route::get('departments', 'index')->name('department.index');
        Route::post('department/store', 'store')->name('department.store');
        Route::put('department/update/{department}', 'update')->name('department.update');
        Route::get('department/delete/{department}', 'delete')->name('department.delete');
    });
    // Employee
    Route::controller(EmployeeController::class)->group(function () {
        Route::get('employees', 'index')->name('employee.index');
        Route::get('employee/create', 'create')->name('employee.create');
        Route::post('employee/store', 'store')->name('employee.store');
        Route::get('employee/edit/{employee}', 'edit')->name('employee.edit');
        Route::put('employee/update/{employee}', 'update')->name('employee.update');
        Route::get('employee/delete/{employee}', 'destroy')->name('employee.delete');
    });
    // Attendance
    Route::controller(AttendanceController::class)->group(function () {
        Route::get('attendance', 'index')->name('attendance.index');
        Route::post('attendance/store', 'store')->name('attendance.store');
        Route::get('attendance/delete/{attendance}', 'delete')->name('attendance.delete');
    });
    // Holiday
    Route::controller(HolidayController::class)->group(function () {
        Route::get('holidays', 'index')->name('holiday.index');
        Route::post('holiday/store', 'store')->name('holiday.store');
        Route::put('holiday/update/{holiday}', 'update')->name('holiday.update');
        Route::get('holiday/delete/{holiday}', 'delete')->name('holiday.delete');
    });
    // Payroll
    Route::controller(PayrollController::class)->group(function () {
        Route::get('payrolls', 'index')->name('payroll.index');
        Route::post('payroll/store', 'store')->name('payroll.store');
        Route::put('payroll/update/{payroll}', 'update')->name('payroll.update');
        Route::get('payroll/delete/{payroll}', 'delete')->name('payroll.delete');
    });







});
Route::controller(ManualInstallController::class)->group(function () {
    Route::get('/seeder-run', 'seederRun')->name('seeder.run.index');
    Route::get('/storage-install', 'storageInstall')->name('storage.install.index');
});
// Change Language
Route::get('/change-language', function () {
    if (request()->ln) {
        App::setLocale(\request()->ln);
        session()->put('local', \request()->ln);
    }
    return back();
})->name('change.local');



    // SuperAdmin Role Management
    Route::controller(SuperAdminRoleController::class)->middleware('auth')->group(function () {
        Route::get('super-admin/roles', 'index')->name('superadmin.roles.index');
        Route::get('super-admin/roles/create', 'create')->name('superadmin.roles.create');
        Route::post('super-admin/roles', 'store')->name('superadmin.roles.store');
        Route::get('super-admin/roles/{role}/edit', 'edit')->name('superadmin.roles.edit');
        Route::put('super-admin/roles/{role}', 'update')->name('superadmin.roles.update');
        Route::delete('super-admin/roles/{role}', 'destroy')->name('superadmin.roles.destroy');
        Route::get('super-admin/roles/{role}/permissions', 'permissions')->name('superadmin.role.permissions');
        Route::post('super-admin/roles/{role}/permissions', 'updatePermissions')->name('superadmin.role.permissions.update');
    });;

    // SuperAdmin User Management
    Route::controller(UserRoleController::class)->middleware('auth')->group(function () {
        Route::get('super-admin/users', 'index')->name('superadmin.users');
        Route::get('super-admin/users/{user}', 'show')->name('superadmin.users.show');
        Route::post('super-admin/user-role/{user}', 'assignRole')->name('superadmin.user.role');
        Route::delete('super-admin/users/{user}/role/{role}', 'removeRole')->name('superadmin.user.role.remove');
    });

    // SuperAdmin Permission Management
    Route::controller(PermissionController::class)->middleware('auth')->group(function () {
        Route::get('super-admin/permissions', 'index')->name('superadmin.permissions');
        Route::get('super-admin/permissions/create', 'create')->name('superadmin.permissions.create');
        Route::post('super-admin/permissions/store', 'store')->name('superadmin.permissions.store');
        Route::get('super-admin/permissions/{permission}/edit', 'edit')->name('superadmin.permissions.edit');
        Route::put('super-admin/permissions/{permission}', 'update')->name('superadmin.permissions.update');
        Route::delete('super-admin/permissions/{permission}', 'destroy')->name('superadmin.permissions.delete');
    });
