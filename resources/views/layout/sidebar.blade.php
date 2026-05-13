@php
    $request = request();
@endphp
<div class="app-sidebar">
    <div class="scrollbar-sidebar">
        <div class="branding-logo">
            @if ($general_settings->dark_mode == 0)
                <img src="{{ $general_settings->logo->file ?? asset('/logo/logo.png') }}" alt="">
            @elseif ($general_settings->dark_mode == 1 && config('app.env') == 'local')
                <img src="{{ asset('/logo/dark-logo.png') }}" alt="">
            @endif
        </div>
        <div class="branding-logo-forMobile">
            <a href="{{ route('root') }}"><img
                    src="{{ isset($general_settings->smallLogo->file) && $general_settings->smallLogo->file ? $general_settings->smallLogo->file : asset('/logo/small_logo.png') }}"
                    alt=""></a>
        </div>
        <div class="app-sidebar-inner">
            <ul class="vertical-nav-menu">
                @can('root')
                    <li>
                        <a class="menu {{ $request->routeIs('root') ? 'active' : '' }}" href="{{ route('root') }}">
                            <span>
                                <img src="{{ asset('icons/menu.svg') }}" class="menu-icon" alt="icon" />
                                {{ __('dashboard') }}
                            </span>
                        </a>
                    </li>
                @endcan
                @can('dashboard')
                    <li>
                        <a class="menu {{ $request->routeIs('dashboard') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}">
                            <span>
                                <img src="{{ asset('icons/menu.svg') }}" class="menu-icon" alt="icon" />
                                {{ __('dashboard') }}
                            </span>
                        </a>
                    </li>
                @endcan
                @canany(['category.index', 'product.index', 'barcode.print', 'brand.index', 'unit.index',
                    'warehouse.index'])
                    <li>
                        <a class="menu {{ $request->routeIs('category.*', 'product.*', 'barcode.print', 'warehouse.*', 'unit.*', 'brand.*') ? 'active' : '' }}"
                            data-bs-toggle="collapse" href="#productMenu">
                            <span>
                                <img src="{{ asset('icons/product.svg') }}" class="menu-icon" alt="icon" />
                                {{ __('product') }}
                            </span>
                            <img src="{{ asset('icons/arrowDown.svg') }}" alt="" class="downIcon">
                        </a>
                        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('category.*', 'product.*', 'barcode.print', 'warehouse.*', 'unit.*', 'brand.*') ? 'show' : '' }}"
                            id="productMenu">
                            <div class="listBar">
                                @can('category.index')
                                    <a href="{{ route('category.index') }}"
                                        class="subMenu {{ $request->routeIs('category.index') ? 'active' : '' }}">
                                        {{ __('categories') }}
                                    </a>
                                @endcan
                                @can('product.index')
                                    <a href="{{ route('product.index') }}"
                                        class="subMenu {{ $request->routeIs('product.*') ? 'active' : '' }}">
                                        {{ __('products') }}
                                    </a>
                                @endcan
                                @if (feature('barcodes'))
                                    @can('barcode.print')
                                        <a href="{{ route('barcode.print') }}"
                                            class="subMenu {{ $request->routeIs('barcode.print') ? 'active' : '' }}">
                                            {{ __('print_barcode') }}
                                        </a>
                                    @endcan
                                @endif
                                @can('unit.index')
                                    <a href="{{ route('unit.index') }}"
                                        class="subMenu {{ $request->routeIs('unit.index') ? 'active' : '' }}">{{ __('units') }}</a>
                                @endcan
                                @can('brand.index')
                                    <a href="{{ route('brand.index') }}"
                                        class="subMenu {{ $request->routeIs('brand.index') ? 'active' : '' }}">{{ __('brands') }}</a>
                                @endcan
                                @if (feature('barcodes'))
                                    @can('barcode.symbology.index')
                                        <a href="{{ route('barcode.symbology.index') }}"
                                        class="subMenu {{ $request->routeIs('barcode.symbology.index') ? 'active' : '' }}">{{ __('barcode_symbology') }}</a>
                                    @endcan
                                @endif
                                @can('warehouse.index')
                                    <a href="{{ route('warehouse.index') }}"
                                        class="subMenu {{ $request->routeIs('warehouse.index') ? 'active' : '' }}">{{ __('warehouses') }}</a>
                                @endcan
                            </div>
                        </div>
                    </li>
                @endcanany
                @canany(['shop.category.index', 'shop.index'])
                    <li>
                        <a class="menu {{ $request->routeIs('shop.category.*', 'shop.*') ? 'active' : '' }}"
                            data-bs-toggle="collapse" href="#shopCategoryMenu">
                            <span>
                                <img src="{{ asset('icons/shop.svg') }}" class="menu-icon" alt="icon" />
                                {{ __('shop') }}
                            </span>
                            <img src="{{ asset('icons/arrowDown.svg') }}" alt="" class="downIcon">
                        </a>
                        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('shop.category.*', 'shop.*') ? 'show' : '' }}"
                            id="shopCategoryMenu">
                            <div class="listBar">
                                @can('shop.category.index')
                                    <a href="{{ route('shop.category.index') }}"
                                        class="subMenu {{ $request->routeIs('shop.category.index') ? 'active' : '' }}">
                                        {{ __('categories') }}
                                    </a>
                                @endcan
                                @can('shop.index')
                                    <a href="{{ route('shop.index') }}"
                                        class="subMenu {{ $request->routeIs('shop.index') ? 'active' : '' }}">
                                        {{ __('list') }}
                                    </a>
                                @endcan
                                @can('shop.create')
                                    <a href="{{ route('shop.create') }}"
                                        class="subMenu {{ $request->routeIs('shop.create') ? 'active' : '' }}">
                                        {{ __('create') }}
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </li>
                @endcanany
                
                @canany(['purchase.index', 'purchase.create', 'stock.count.index', 'purchase.batch'])
                    <li>
                        <a class="menu {{ $request->routeIs(['purchase.index', 'purchase.create', 'stock.count.index', 'purchase.batch']) ? 'active' : '' }}"
                            data-bs-toggle="collapse" href="#purchaseMenu">
                            <span>
                                <img src="{{ asset('icons/purchase.svg') }}" class="menu-icon" alt="icon" />
                                {{ __('purchase') }}
                            </span>
                            <img src="{{ asset('icons/arrowDown.svg') }}" alt="" class="downIcon">
                        </a>
                        <div class="collapse dropdownMenuCollapse {{ $request->routeIs(['purchase.index', 'purchase.create', 'stock.count.index', 'purchase.batch']) ? 'show' : '' }}"
                            id="purchaseMenu">
                            <div class="listBar">
                                @can('purchase.index')
                                    <a class="subMenu {{ $request->routeIs('purchase.index', 'purchase.edit') ? 'active' : '' }}"
                                        href="{{ route('purchase.index') }}">{{ __('purchases') }}</a>
                                @endcan
                                @can('purchase.create')
                                    <a class="subMenu {{ $request->routeIs('purchase.create') ? 'active' : '' }}"
                                        href="{{ route('purchase.create') }}">{{ __('add_purchase') }}</a>
                                @endcan
                                @can('stock.count.index')
                                    <a class="subMenu {{ $request->routeIs('stock.count.index') ? 'active' : '' }}"
                                        href="{{ route('stock.count.index') }}">{{ __('stock_count') }}</a>
                                @endcan
                                @if (feature('product_batch_and_expire_date'))
                                    @can('purchase.batch')
                                        <a class="subMenu {{ $request->routeIs('purchase.batch') ? 'active' : '' }}"
                                            href="{{ route('purchase.batch') }}">{{ __('batches') }}</a>
                                    @endcan
                                @endif
                            </div>
                        </div>
                    </li>
                @endcanany
           
                @if(hasSubscriptionFeature('accounting'))
                    @canany(['account.index', 'money.transfer.index', 'account.balancesheet'])
                        <li>
                            <a class="menu {{ $request->routeIs('account.*', 'money.transfer.*') ? 'active' : '' }}"
                                data-bs-toggle="collapse" href="#accountMenu">
                                <span>
                                    <img src="{{ asset('icons/account.svg') }}" class="menu-icon" alt="icon" />
                                    {{ __('accounting') }}
                                </span>
                                <img src="{{ asset('icons/arrowDown.svg') }}" alt="" class="downIcon">
                            </a>
                            <div class="collapse dropdownMenuCollapse {{ $request->routeIs('account.*', 'money.transfer.*') ? 'show' : '' }}"
                                id="accountMenu">
                                <div class="listBar">
                                    @can('account.index')
                                        <a href="{{ route('account.index') }}"
                                            class="subMenu {{ $request->routeIs('account.index') ? 'active' : '' }}">{{ __('accounts') }}</a>
                                    @endcan
                                    @can('money.transfer.index')
                                        <a href="{{ route('money.transfer.index') }}"
                                            class="subMenu {{ $request->routeIs('money.transfer.index') ? 'active' : '' }}">
                                            {{ __('money_transfer') }}
                                        </a>
                                    @endcan
                                    @can('account.balancesheet')
                                        <a href="{{ route('account.balancesheet') }}"
                                            class="subMenu {{ $request->routeIs('account.balancesheet') ? 'active' : '' }}">
                                            {{ __('balance_sheet') }}
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </li>
                    @endcanany
                @endif
                @canany(['sale.index', 'sale.draft'])
                    <li>
                        <a class="menu {{ $request->routeIs(['sale.index', 'sale.draft']) ? 'active' : '' }}"
                            data-bs-toggle="collapse" href="#saleMenu">
                            <span>
                                <img src="{{ asset('icons/Activity.svg') }}" class="menu-icon" alt="icon" />
                                {{ __('sales') }}
                            </span>
                            <img src="{{ asset('icons/arrowDown.svg') }}" alt="" class="downIcon">
                        </a>
                        <div class="collapse dropdownMenuCollapse {{ $request->routeIs(['sale.index', 'sale.draft']) ? 'show' : '' }}"
                            id="saleMenu">
                            <div class="listBar">
                                @can('sale.index')
                                    <a href="{{ route('sale.index') }}"
                                        class="subMenu {{ $request->routeIs('sale.index') ? 'active' : '' }}">
                                        {{ __('sales') }}
                                    </a>
                                @endcan
                                @can('sale.draft')
                                    <a href="{{ route('sale.draft') }}"
                                        class="subMenu {{ $request->routeIs('sale.draft') ? 'active' : '' }}">
                                        {{ __('drafts') }}
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </li>
                @endcanany
                @if(hasSubscriptionFeature('return'))
                    @canany(['sale.return.index'])
                        <li>
                            <a class="menu {{ $request->routeIs('sale.return.index') ? 'active' : '' }}"
                                data-bs-toggle="collapse" href="#saleReturnsMenu">
                                <span>
                                    <img src="{{ asset('icons/return.svg') }}" class="menu-icon" alt="icon" />
                                    {{ __('returns') }}
                                </span>
                                <img src="{{ asset('icons/arrowDown.svg') }}" alt="" class="downIcon">
                            </a>
                            <div class="collapse dropdownMenuCollapse {{ $request->routeIs('sale.return.index') ? 'show' : '' }}"
                                id="saleReturnsMenu">
                                <div class="listBar">
                                    @can('sale.return.index')
                                        <a href="{{ route('sale.return.index') }}"
                                            class="subMenu {{ $request->routeIs('sale.return*') ? 'active' : '' }}">
                                            {{ __('sales') }}
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </li>
                    @endcanany
                @endif
                @if(hasSubscriptionFeature('expense'))
                    @canany(['expenseCategory.index', 'expense.index'])
                        <li>
                            <a class="menu {{ $request->routeIs('expenseCategory.*', 'expense.*') ? 'active' : '' }}"
                                data-bs-toggle="collapse" href="#expenseMenu">
                                <span>
                                    <img src="{{ asset('icons/money-dollar.svg') }}" class="menu-icon" alt="icon" />
                                    {{ __('expense') }}
                                </span>
                                <img src="{{ asset('icons/arrowDown.svg') }}" alt="" class="downIcon">
                            </a>
                            <div class="collapse dropdownMenuCollapse {{ $request->routeIs('expenseCategory.*', 'expense.*') ? 'show' : '' }}"
                                id="expenseMenu">
                                <div class="listBar">
                                    @can('expenseCategory.index')
                                        <a href="{{ route('expenseCategory.index') }}"
                                            class="subMenu {{ $request->routeIs('expenseCategory.*') ? 'active' : '' }}">
                                            {{ __('expense_category') }}
                                        </a>
                                    @endcan
                                    @can('expense.index')
                                        <a href="{{ route('expense.index') }}"
                                            class="subMenu {{ $request->routeIs('expense.*') ? 'active' : '' }}">
                                            {{ __('expense') }}
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </li>
                    @endcanany
                @endif
                @if(hasSubscriptionFeature('hrm'))
                    @canany(['department.index', 'employee.index', 'role.index', 'attendance.index', 'holiday.index',
                        'payroll.index'])
                        <li>
                            <a class="menu {{ $request->routeIs('department.*', 'role.index', 'employee.*', 'attendance.*', 'holiday.*', 'payroll.*') ? 'active' : '' }}"
                                data-bs-toggle="collapse" href="#departmentMenu">
                                <span>
                                    <img src="{{ asset('icons/user-polygon.svg') }}" class="menu-icon" alt="icon" />
                                    {{ __('hrm') }}
                                </span>
                                <img src="{{ asset('icons/arrowDown.svg') }}" alt="" class="downIcon">
                            </a>
                            <div class="collapse dropdownMenuCollapse {{ $request->routeIs('department.*', 'role.*', 'employee.*', 'attendance.*', 'holiday.*', 'payroll.*') ? 'show' : '' }}"
                                id="departmentMenu">
                                <div class="listBar">
                                    @can('department.index')
                                        <a href="{{ route('department.index') }}"
                                            class="subMenu {{ $request->routeIs('department.*') ? 'active' : '' }}">
                                            {{ __('departments') }}
                                        </a>
                                    @endcan
                                    @can('role.index')
                                        <a href="{{ route('role.index') }}"
                                            class="subMenu {{ $request->routeIs('role.*') ? 'active' : '' }}">
                                            {{ __('role_permission') }}
                                        </a>
                                    @endcan
                                    @can('employee.index')
                                        <a href="{{ route('employee.index') }}"
                                            class="subMenu {{ $request->routeIs('employee.*') ? 'active' : '' }}">
                                            {{ __('employees') }}
                                        </a>
                                    @endcan
                                    @can('attendance.index')
                                        <a href="{{ route('attendance.index') }}"
                                            class="subMenu {{ $request->routeIs('attendance.*') ? 'active' : '' }}">
                                            {{ __('attendance') }}
                                        </a>
                                    @endcan
                                    @can('payroll.index')
                                        <a href="{{ route('payroll.index') }}"
                                            class="subMenu {{ $request->routeIs('payroll.*') ? 'active' : '' }}">
                                            {{ __('payrolls') }}
                                        </a>
                                    @endcan
                                    @can('holiday.index')
                                        <a href="{{ route('holiday.index') }}"
                                            class="subMenu {{ $request->routeIs('holiday.*') ? 'active' : '' }}">
                                            {{ __('holidays') }}
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </li>
                    @endcanany
                @endif
                @canany(['customer.index', 'supplier.index', 'customer.group.index'])
                    <li>
                        <a class="menu {{ $request->routeIs('customer.*', 'supplier.*', 'customer.group.*') ? 'active' : '' }}"
                            data-bs-toggle="collapse" href="#peopleMenu">
                            <span>
                                <img src="{{ asset('icons/users.svg') }}" class="menu-icon" alt="icon" />
                                {{ __('people') }}
                            </span>
                            <img src="{{ asset('icons/arrowDown.svg') }}" alt="" class="downIcon">
                        </a>
                        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('customer.*', 'supplier.*', 'customer.group.*') ? 'show' : '' }}"
                            id="peopleMenu">
                            <div class="listBar">
                                @can('customer.index')
                                    <a href="{{ route('customer.index') }}"
                                        class="subMenu {{ $request->routeIs(['customer.index', 'customer.edit', 'customer.create']) ? 'active' : '' }}">
                                        {{ __('customers') }}
                                    </a>
                                @endcan
                                @can('customer.group.index')
                                    <a href="{{ route('customer.group.index') }}"
                                        class="subMenu {{ $request->routeIs('customer.group.index') ? 'active' : '' }}">
                                        {{ __('customer_groups') }}
                                    </a>
                                @endcan
                                @can('supplier.index')
                                    <a href="{{ route('supplier.index') }}"
                                        class="subMenu {{ $request->routeIs('supplier.*') ? 'active' : '' }}">
                                        {{ __('suppliers') }}
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </li>
                @endcanany
                @canany(['report.summary', 'report.profit.loss', 'report.purchases', 'report.sales', 'report.product',
                    'report.expense'])
                    <li>
                        <a class="menu {{ $request->routeIs('report.*') ? 'active' : '' }}" data-bs-toggle="collapse"
                            href="#reportMenu">
                            <span>
                                <img src="{{ asset('icons/report.svg') }}" class="menu-icon" alt="icon" />
                                {{ __('reports') }}
                            </span>
                            <img src="{{ asset('icons/arrowDown.svg') }}" alt="" class="downIcon">
                        </a>
                        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('report.*') ? 'show' : '' }}"
                            id="reportMenu">
                            <div class="listBar">
                                @can('report.summary')
                                    <a href="{{ route('report.summary') }}"
                                        class="subMenu {{ $request->routeIs('report.summary') ? 'active' : '' }}">
                                        {{ __('summary') }}
                                    </a>
                                @endcan
                                @can('report.profit.loss')
                                    <a href="{{ route('report.profit.loss') }}"
                                        class="subMenu {{ $request->routeIs('report.profit.loss') ? 'active' : '' }}">
                                        {{ __('profit_loss') }}
                                    </a>
                                @endcan
                                @if (feature('purchases'))
                                    @can('report.purchases')
                                        <a href="{{ route('report.purchases') }}"
                                            class="subMenu {{ $request->routeIs('report.purchases') ? 'active' : '' }}">
                                            {{ __('purchases') }}
                                        </a>
                                    @endcan
                                @endif
                                @can('report.sales')
                                    <a href="{{ route('report.sales') }}"
                                        class="subMenu {{ $request->routeIs('report.sales') ? 'active' : '' }}">
                                        {{ __('sales') }}
                                    </a>
                                @endcan
                                @can('report.product')
                                    <a href="{{ route('report.product') }}"
                                        class="subMenu {{ $request->routeIs('report.product') ? 'active' : '' }}">
                                        {{ __('products') }}
                                    </a>
                                @endcan
                                @can('report.expense')
                                    <a href="{{ route('report.expense') }}"
                                        class="subMenu {{ $request->routeIs('report.expense') ? 'active' : '' }}">
                                        {{ __('expense') }}
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </li>
                @endcanany
                @canany(['subscription.index', 'subscription.purchase.index'])
                    <li>
                        <a class="menu {{ $request->routeIs('subscription.*', 'subscription.purchase.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#subscriptionMenu">
                            <span>
                                <img src="{{ asset('icons/money-coin.svg') }}" class="menu-icon" alt="icon" />
                                {{ __('subscriptions') }}
                            </span>
                            <img src="{{ asset('icons/arrowDown.svg') }}" alt="" class="downIcon">
                        </a>
                        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('subscription.*', 'subscription.purchase.*') ? 'show' : '' }}" id="subscriptionMenu">
                            <div class="listBar">
                                @can('subscription.index')
                                    <a href="{{ route('subscription.index') }}" class="subMenu {{ $request->routeIs('subscription.index') ? 'active' : '' }}">
                                        {{ __('list') }}
                                    </a>
                                @endcan
                                @can('subscription.purchase.index')
                                    <a href="{{ route('subscription.purchase.index') }}"
                                        class="subMenu {{ $request->routeIs(['subscription.purchase.index', 'subscription.purchase.update']) ? 'active' : '' }}">
                                        {{ __('purchase') }}
                                    </a>
                                @endcan
                                @can('subscription.report')
                                    <a href="{{ route('subscription.report') }}"
                                        class="subMenu {{ $request->routeIs('subscription.report') ? 'active' : '' }}">
                                        {{ __('reports') }}
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </li>
                @endcanany

                <!-- SUPER ADMIN: Role & Permission Management -->
                @role('super admin')
                    <li>
                        <a class="menu {{ $request->routeIs('superadmin.roles.*', 'superadmin.permissions.*', 'superadmin.users*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#adminToolsMenu">
                            <span>
                                <img src="{{ asset('icons/Setting.svg') }}" class="menu-icon" alt="icon" />
                                {{ __('Admin Tools') }}
                            </span>
                            <img src="{{ asset('icons/arrowDown.svg') }}" alt="" class="downIcon">
                        </a>
                        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('superadmin.roles.*', 'superadmin.permissions.*', 'superadmin.users*') ? 'show' : '' }}" id="adminToolsMenu">
                            <div class="listBar">
                                <a href="{{ route('superadmin.roles.index') }}" class="subMenu {{ $request->routeIs('superadmin.roles.*') ? 'active' : '' }}">
                                    {{ __('Roles') }}
                                </a>
                                <a href="{{ route('superadmin.permissions') }}" class="subMenu {{ $request->routeIs('superadmin.permissions*') ? 'active' : '' }}">
                                    {{ __('Permissions') }}
                                </a>
                                <a href="{{ route('superadmin.users') }}" class="subMenu {{ $request->routeIs('superadmin.users*') ? 'active' : '' }}">
                                    {{ __('User Management') }}
                                </a>
                            </div>
                        </div>
                    </li>
                @endrole

                    @canany(['store.index'])
                        <li>
                            <a class="menu {{ $request->routeIs('store.*') ? 'active' : '' }}" data-bs-toggle="collapse"
                                href="#storeMenu">
                                <span>
                                    <img src="{{ asset('icons/shop.svg') }}" class="menu-icon" alt="icon" />
                                    {{ __('store') }}
                                </span>
                                <img src="{{ asset('icons/arrowDown.svg') }}" alt="" class="downIcon">
                            </a>
                            <div class="collapse dropdownMenuCollapse {{ $request->routeIs('store.*') ? 'show' : '' }}"
                                id="storeMenu">
                                <div class="listBar">
                                    @can('store.index')
                                        <a href="{{ route('store.index') }}"
                                            class="subMenu {{ $request->routeIs(['store.index', 'store.edit']) ? 'active' : '' }}">
                                            {{ __('list') }}
                                        </a>
                                    @endcan
                                    @can('store.create')
                                        <a href="{{ route('store.create') }}"
                                            class="subMenu {{ $request->routeIs('store.create') ? 'active' : '' }}">
                                            {{ __('create') }}
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </li>
                    @endcanany
                    @canany(['coupon.index', 'currency.index', 'tax.index', 'profile.index', 'settings.general',
                        'payment-gateway', 'landing.install'])
                        <li>
                            <a class="menu {{ $request->routeIs('coupon.*', 'currency.*', 'tax.*', 'profile.*', 'settings.*', 'payment-gateway.*') ? 'active' : '' }}"
                                data-bs-toggle="collapse" href="#SettingMenu">
                                <span>
                                    <img src="{{ asset('icons/Setting.svg') }}" class="menu-icon" alt="icon" />
                                    {{ __('settings') }}
                                </span>
                                <img src="{{ asset('icons/arrowDown.svg') }}" alt="" class="downIcon">
                            </a>
                            <div class="collapse dropdownMenuCollapse {{ $request->routeIs('coupon.*', 'currency.*', 'tax.*', 'profile.*', 'settings.*', 'payment-gateway.*') ? 'show' : '' }}"
                                id="SettingMenu">
                                <div class="listBar">
                                    @can('coupon.index')
                                        <a href="{{ route('coupon.index') }}"
                                            class="subMenu {{ $request->routeIs('coupon.index') ? 'active' : '' }}">{{ __('coupon') }}</a>
                                    @endcan
                                    @can('currency.index')
                                        <a href="{{ route('currency.index') }}"
                                            class="subMenu {{ $request->routeIs('currency.index') ? 'active' : '' }}">{{ __('currencies') }}</a>
                                    @endcan
                                    @can('tax.index')
                                        <a href="{{ route('tax.index') }}"
                                            class="subMenu {{ $request->routeIs('tax.index') ? 'active' : '' }}">{{ __('taxs') }}</a>
                                    @endcan
                                    @can('profile.index')
                                        <a href="{{ route('profile.index') }}"
                                            class="subMenu {{ $request->routeIs('profile.index') ? 'active' : '' }}">{{ __('profile') }}</a>
                                    @endcan
                                    @can('payment-gateway.index')
                                        <a href="{{ route('payment-gateway.index') }}"
                                            class="subMenu {{ $request->routeIs('payment-gateway.index') ? 'active' : '' }}">{{ __('payment_gateway') }}</a>
                                    @endcan
                                    @can('settings.mail')
                                        <a href="{{ route('settings.mail') }}"
                                            class="subMenu {{ $request->routeIs('settings.mail') ? 'active' : '' }}">{{ __('smtp_configure') }}</a>
                                    @endcan
                                    @can('settings.general')
                                        <a href="{{ route('settings.general') }}"
                                            class="subMenu {{ $request->routeIs('settings.general') ? 'active' : '' }}">{{ __('general_settings') }}</a>
                                    @endcan
                                    <!-- @can('settings.business.modules')
                                        <a href="{{ route('settings.business.modules') }}"
                                            class="subMenu {{ $request->routeIs('settings.business.modules') ? 'active' : '' }}">{{ __('business_modules') }}</a>
                                    @endcan -->

                                    <!-- @can('shop-payment-gateway.index')
                                        <a href="{{ route('shop-payment-gateway.index') }}"
                                            class="subMenu {{ $request->routeIs('shop-payment-gateway.index') ? 'active' : '' }}">{{ __('payment_gateway') }}</a>
                                    @endcan -->
                                    @can('settings.database.backup')
                                        <a href="#" id="databaseDownloadConfirm"
                                            class="subMenu">{{ __('data_base_backup') }}</a>
                                    @endcan

                                    <!-- @can('landing.install')
                                        <a href="{{ route('landing.install') }}"
                                        class="subMenu {{ $request->routeIs('landing.install') ? 'active' : '' }}">{{ __('landing_install') }}</a>
                                    @endcan -->
                                </div>
                            </div>
                        </li>
                    @endcanany
                    @can('language.index')
                        <li>
                            <a class="menu {{ $request->routeIs('language.*') ? 'active' : '' }}"
                                href="{{ route('language.index') }}">
                                <span>
                                    <img src="{{ asset('icons/language.svg') }}" class="menu-icon" alt="icon" />
                                    {{ __('language') }}
                                </span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
            @canany(['subscription.purchase.index'])
                @php
                    $subscription = $mainShop?->currentSubscriptions();
                @endphp
                @if ($subscription)
                    <div class="subscription-box">
                        <div class="subscription-box-title"><img src="{{ asset('icons/crown.svg') }}" class="menu-icon"
                                alt="icon" />
                            {{ __('your_current_subscription_name_is') }} {{ $subscription->title }}
                            {{ __('and_this_subscription_will_expires_in') }} {{ dateFormat($subscription->expired_at) }}
                        </div>
                    </div>
                @endif
            @endcanany
            <div class="sideBarfooter">
                <button type="button" class="fullbtn hite-icon" onclick="toggleFullScreen(document.body)"><i
                        class="fa-solid fa-expand"></i></button>
                <a href="{{ route('settings.general') }}" class="fullbtn hite-icon"><i class="fa-solid fa-cog"></i></a>
                <a href="{{ route('profile.index', auth()->id()) }}" class="fullbtn hite-icon"><i
                        class="fa-solid fa-user"></i></a>
                <a onclick="signout()" class="fullbtn hite-icon"><i class="fa-solid fa-power-off"></i></a>
            </div>
        </div>
    </div>
