@extends('layout.auth')
@section('title', __('signin'))
@section('content')
    <style>
        .version-text {
            position: absolute;
            right: 10px;
            font-size: 15px;
            top: 10px;
            font-weight: 600;
            color: #3bb2fb;
            padding: 0 5px;
            background-color: #3bb2fb36;
            margin-right: 15px;
            border-radius: 5px;
        }
    </style>
    <form action="{{ route('signin.request') }}" method="POST">
        <span class="version-text">V: 3.1.0</span>
        @csrf
        <a href="{{ Route::has('home') ? route('home') : '#' }}">
            <div class="logo-img text-center">
                <img src="{{ $general_settings->logo->file ?? asset('/logo/logo.png') }}" alt="">
            </div>
        </a>
        <div class="page-content">
            <h2 class="pageTitle">{{ __('welcome_to') }} <span
                    style="color:#3BB2FB">{{ isset($general_settings->site_title) && $general_settings->site_title ? $general_settings->site_title : 'Ready POS' }}</span>
            </h2>
            <h1 class="signin-heading">{{ __('sign_in') }}</h1>
        </div>

        <div class="form-outline form-white mb-3">
            <label class="mb-2">{{ __('enter_your_email') }}</label>
            <input type="email" name="email" id="email" class="form-control mb-1" placeholder="{{ __('email') }}">
            @error('email')
                <span class="text text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-outline form-white mb-3">
            <label class="mb-2">{{ __('enter_your_assword') }}</label>
            <div class="position-relative">
                <input type="password" id="password" name="password" class="form-control mb-1"
                    placeholder="{{ __('password') }}">
                <span class="eye" onclick="showHidePassword()">
                    <i class="far fa-eye fa-eye-slash" id="togglePassword"></i>
                </span>
            </div>
            @error('password')
                <span class="text text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <button class="btn loginButton" type="submit">{{ __('sign_in') }}</button>
        <span class="text-center w-100 d-block pt-2">{{ __('register_yourself_as_a_shop_owner') }} <a
                href="{{ route('signup.index') }}">{{ __('signup') }}</a></span>

        @if (config('app.env') == 'local')
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class=" d-flex justify-content-center gap-2 flex-wrap">
                        <button type="submit" class="btn btn-primary" id="admin">All In One</button>
                        <button type="submit" class="btn btn-primary" id="groceryShop">Super Shop/Grocery</button>
                        <button type="submit" class="btn btn-primary" id="pharmacyShop">Pharmacy</button>
                        <button type="submit" class="btn btn-primary" id="mobileShop">Electronics/Hardware or Mobile
                            Shop</button>
                        <button type="submit" class="btn btn-primary" id="restaurant">Restaurant</button>
                        <button type="submit" class="btn btn-primary" id="super_admin">Super Admin or SAAS</button>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="small text-center mt-2 text-danger">In this above button demo and local purpose
                    </div>
                </div>
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

    <script>
        function showHidePassword() {
            const toggle = document.getElementById("togglePassword");
            const password = document.getElementById("password");

            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            // toggle the icon
            toggle.classList.toggle("fa-eye");
        }
    </script>
@endpush
