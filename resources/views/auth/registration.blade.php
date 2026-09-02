@extends('layout.auth')
@section('title', __('signup'))
@section('content')
        <form action="{{ route('signup.request') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <a href="{{ Route::has('home') ? route('home') : '#' }}">
                <div class="text-center">
                    <img class="mx-auto h-16 w-auto object-contain" src="{{ $general_settings->logo->file ?? asset('/logo/logo.png') }}" alt="{{ $general_settings->site_title ?? 'Ready POS' }}">
                </div>
                <div class="mt-4 text-center">
                    <h2 class="text-lg text-slate-500">
                        {{ __('welcome_to') }} <span class="font-semibold text-sky-500">{{ $general_settings->site_title ?? 'Ready POS' }}</span>
                    </h2>
                    <h1 class="mt-1 text-3xl font-bold text-slate-800">{{ __('sign_up') }}</h1>
                </div>
            </a>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700" for="name">{{ __('enter_your_name') }}</label>
                <input type="text" name="name" id="name" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-800 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100" placeholder="{{ __('name') }}">
                @error('name')
                    <span class="mt-1 block text-sm text-red-600" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
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
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700" for="shop_name">{{ __('enter_your_shop_name') }}</label>
                <input type="text" name="shop_name" id="shop_name" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-800 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                    placeholder="{{ __('shop_name') }}">
                @error('shop_name')
                    <span class="mt-1 block text-sm text-red-600" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700" for="shop_category_id">{{ __('select_a_shop_category') }}</label>
                <select name="shop_category_id" id="shop_category_id" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-800 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                    <option selected disabled>{{ __('select_a_option') }}</option>
                    @if ($shopCategories->isNotEmpty())
                        @foreach ($shopCategories as $shopCategory)
                            <option value="{{ $shopCategory->id }}">{{ $shopCategory->name }}</option>
                        @endforeach
                    @endif
                </select>
                @error('shop_category_id')
                    <span class="mt-1 block text-sm text-red-600" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700" for="shop_logo">{{ __('select_a_shop_logo') }}</label>
                <input type="file" name="shop_logo" id="shop_logo" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-600 file:mr-3 file:rounded-md file:border-0 file:bg-sky-50 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-sky-700">
                @error('shop_logo')
                    <span class="mt-1 block text-sm text-red-600" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700" for="shop_favicon">{{ __('select_a_shop_favicon') }}</label>
                <input type="file" name="shop_favicon" id="shop_favicon" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-600 file:mr-3 file:rounded-md file:border-0 file:bg-sky-50 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-sky-700">
                @error('shop_favicon')
                    <span class="mt-1 block text-sm text-red-600" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            </div>
            <button class="w-full rounded-lg bg-sky-500 px-4 py-3 font-semibold text-white transition hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-300" type="submit">{{ __('sign_up') }}</button>
            <p class="text-center text-sm text-slate-500">{{ __('already_have_a_account') }} <a class="font-semibold text-sky-600 hover:underline" href="{{ route('signin.index') }}">{{ __('signin') }}</a></p>
        </form>
@endsection
