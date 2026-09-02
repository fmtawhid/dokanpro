<!doctype html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <!-- Meta-Link -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="mlapplication-tap-highlight" content="no">
    <!-- FaveIcon-Link -->
    <link rel="icon" type="image/png"
        href="{{ isset($general_settings->favicon->file) && $general_settings->favicon->file ? $general_settings->favicon->file : asset('/logo/small_logo.png') }}" />
    <!-- Title -->
    <title>
        {{ isset($general_settings->site_title) && $general_settings->site_title ? $general_settings->site_title : 'Ready POS' }}
        - @yield('title')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}" type="text/css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="fixed inset-x-0 top-0 z-50 flex flex-col gap-1">
        @if ($seederRun)
            <div class="flex items-center justify-center gap-2 bg-red-100 px-4 py-3 text-center text-sm text-red-900" role="alert">
                <span><strong>Seeder dose not run.</strong> Please run <code class="font-mono text-red-700">php artisan migrate:fresh --seed</code> or <a href="{{ route('seeder.run.index') }}" class="font-semibold underline">click here</a></span>
                <button type="button" class="text-xl leading-none" onclick="this.parentElement.remove()" aria-label="Close">&times;</button>
            </div>
        @endif
        @if ($storageLink)
            <div class="flex items-center justify-center gap-2 bg-red-100 px-4 py-3 text-center text-sm text-red-900" role="alert">
                <span><strong>Storage link dose not exist.</strong> Please run <code class="font-mono text-red-700">php artisan storage:link</code> or <a href="{{ route('storage.install.index') }}" class="font-semibold underline">click here</a></span>
                <button type="button" class="text-xl leading-none" onclick="this.parentElement.remove()" aria-label="Close">&times;</button>
            </div>
        @endif
    </div>

    <main class="flex min-h-screen items-center justify-center bg-slate-100 px-4 py-8 sm:px-6">
        <section class="w-full max-w-2xl rounded-2xl bg-white p-6 shadow-xl shadow-slate-200/70 sm:p-10">
            @yield('content')
        </section>
        </section>
    </main>

    <script src="{{ asset('assets/scripts/jquery-3.6.3.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/sweetalert_modify.js') }}"></script>
    @if (session('success'))
        <script>
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            })
        </script>
    @endif

    <script>
        function showHidePassword() {
            const password = document.getElementById('password');
            const toggle = document.getElementById('togglePassword');

            if (!password || !toggle) return;

            password.type = password.type === 'password' ? 'text' : 'password';
            toggle.textContent = password.type === 'password' ? 'Show' : 'Hide';
        }
    </script>

    @stack('scripts')
</body>

</html>
