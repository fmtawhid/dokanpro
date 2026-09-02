@extends('layout.auth')
@section('title', __('signin'))
@section('content')
    <form action="{{ route('signin.request') }}" method="POST" class="space-y-6">
        <div class="flex items-center justify-between">
            <span class="rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-600">V: 3.1.0</span>
        </div>
        @csrf
        <a href="{{ Route::has('home') ? route('home') : '#' }}">
            <div class="text-center">
                <img class="mx-auto h-16 w-auto object-contain" src="{{ $general_settings->logo->file ?? asset('/logo/logo.png') }}" alt="{{ $general_settings->site_title ?? 'Ready POS' }}">
            </div>
        </a>
        <div class="text-center">
            <h2 class="text-lg text-slate-500">{{ __('welcome_to') }} <span class="font-semibold text-sky-500">{{ $general_settings->site_title ?? 'Ready POS' }}</span></h2>
            <h1 class="mt-1 text-3xl font-bold text-slate-800">{{ __('sign_in') }}</h1>
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium text-slate-700" for="email">{{ __('enter_your_email') }}</label>
            <input type="email" name="email" id="email" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-800 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100" placeholder="{{ __('email') }}">
            @error('email')
                <span class="mt-1 block text-sm text-red-600" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium text-slate-700" for="password">{{ __('enter_your_assword') }}</label>
            <div class="relative">
                <input type="password" id="password" name="password" class="w-full rounded-lg border border-slate-300 px-4 py-3 pr-16 text-slate-800 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                    placeholder="{{ __('password') }}">
                <button type="button" class="absolute inset-y-0 right-3 text-sm font-semibold text-sky-600" onclick="showHidePassword()" id="togglePassword">Show</button>
            </div>
            @error('password')
                <span class="mt-1 block text-sm text-red-600" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <button class="w-full rounded-lg bg-sky-500 px-4 py-3 font-semibold text-white transition hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-300" type="submit">{{ __('sign_in') }}</button>
        <p class="text-center text-sm text-slate-500">{{ __('register_yourself_as_a_shop_owner') }} <a class="font-semibold text-sky-600 hover:underline" href="{{ route('signup.index') }}">{{ __('signup') }}</a></p>

        @if (config('app.env') == 'local')
            <div class="mt-4 border-t border-slate-200 pt-4">
                <div class="flex flex-wrap justify-center gap-2">
                    <button type="submit" class="rounded-md bg-slate-700 px-3 py-2 text-xs font-medium text-white" id="admin">All In One</button>
                    <button type="submit" class="rounded-md bg-slate-700 px-3 py-2 text-xs font-medium text-white" id="groceryShop">Super Shop/Grocery</button>
                    <button type="submit" class="rounded-md bg-slate-700 px-3 py-2 text-xs font-medium text-white" id="pharmacyShop">Pharmacy</button>
                    <button type="submit" class="rounded-md bg-slate-700 px-3 py-2 text-xs font-medium text-white" id="mobileShop">Electronics/Hardware or Mobile Shop</button>
                    <button type="submit" class="rounded-md bg-slate-700 px-3 py-2 text-xs font-medium text-white" id="restaurant">Restaurant</button>
                    <button type="submit" class="rounded-md bg-slate-700 px-3 py-2 text-xs font-medium text-white" id="super_admin">Super Admin or SAAS</button>
                </div>
                <p class="mt-2 text-center text-xs text-red-600">In this above button demo and local purpose</p>
            </div>
        @endif
    </form>
@endsection
@push('scripts')
    <script>
        $('#super_admin').on('click', function() {
            $('#email').val('superadmin@example.com');
            $('#password').val('secret');
        });
        $('#admin').on('click', function() {
            $('#email').val('admin@example.com');
            $('#password').val('secret');
        });
        $('#groceryShop').on('click', function() {
            $('#email').val('groceryshop@example.com');
            $('#password').val('secret');
        });
        $('#pharmacyShop').on('click', function() {
            $('#email').val('pharmacy@example.com');
            $('#password').val('secret');
        });
        $('#mobileShop').on('click', function() {
            $('#email').val('electronics@example.com');
            $('#password').val('secret');
        });
        $('#restaurant').on('click', function() {
            $('#email').val('restaurant@example.com');
            $('#password').val('secret');
        });
    </script>

@endpush
