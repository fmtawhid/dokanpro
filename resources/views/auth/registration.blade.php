@extends('layout.auth')
@section('title', __('signup'))
@section('content')
    {{-- <div class="d-flex justify-content-center align-items-center h-100"> --}}
        <form action="{{ route('signup.request') }}" method="POST" enctype="multipart/form-data" >
            @csrf
            <a href="{{ Route::has('home') ? route('home') : '#' }}">
                <div class="logo-img text-center pt-5">
                    <img src="{{ $general_settings->logo->file ?? asset('/logo/logo.png') }}" alt="">
                </div>
                <div class="page-content">
                    <h2 class="pageTitle">
                        {{ __('welcome_to') }}
                        <span style="color:#3BB2FB">
                            {{ $general_settings->site_title ?? 'Ready POS' }}
                        </span>
                    </h2>
                    <h1 class="signin-heading">{{ __('sign_up') }}</h1>
                </div>
            </a>
            <div class="form-outline form-white mb-2 mb-md-3">
                <label class="mb-2" for="name">{{ __('enter_your_name') }}</label>
                <input type="text" name="name" id="name" class="form-control mb-1" placeholder="{{ __('name') }}">
                @error('name')
                    <span class="text text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-outline form-white mb-2 mb-md-3">
                <label class="mb-2" for="email">{{ __('enter_your_email') }}</label>
                <input type="email" name="email" id="email" class="form-control mb-1" placeholder="{{ __('email') }}">
                @error('email')
                    <span class="text text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-outline form-white mb-2 mb-md-3">
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
            <div class="form-outline form-white mb-3 mb-md-3">
                <label class="mb-2" for="shop_name">{{ __('enter_your_shop_name') }}</label>
                <input type="text" name="shop_name" id="address" class="form-control mb-1"
                    placeholder="{{ __('shop_name') }}">
                @error('shop_name')
                    <span class="text text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-outline form-white mb-2 mb-md-3">
                <label class="mb-2" for="shop_category_id">{{ __('select_a_shop_category') }}</label>
                <select name="shop_category_id" id="shop_category_id"class="form-control mb-1">
                    <option selected disabled>{{ __('select_a_option') }}</option>
                    @if ($shopCategories->isNotEmpty())
                        @foreach ($shopCategories as $shopCategory)
                            <option value="{{ $shopCategory->id }}">{{ $shopCategory->name }}</option>
                        @endforeach
                    @endif
                </select>
                @error('shop_category_id')
                    <span class="text text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-outline form-white mb-2 mb-md-3">
                <label class="mb-2" for="shop_logo">{{ __('select_a_shop_logo') }}</label>
                <input type="file" name="shop_logo" id="shop_logo" class="form-control mb-1">
                @error('shop_logo')
                    <span class="text text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-outline form-white mb-2 mb-md-3">
                <label class="mb-2" for="shop_favicon">{{ __('select_a_shop_favicon') }}</label>
                <input type="file" name="shop_favicon" id="shop_favicon" class="form-control mb-1">
                @error('shop_favicon')
                    <span class="text text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <button class="btn loginButton" type="submit">{{ __('sign_up') }}</button>
            <span class="text-center w-100 d-block pt-2">{{ __('already_have_a_account') }} <a
                    href="{{ route('signin.index') }}">{{ __('signin') }}</a></span>
        </form>
    {{-- </div> --}}
@endsection
@push('scripts')
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
