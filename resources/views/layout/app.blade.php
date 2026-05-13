<!DOCTYPE html>
<html lang="en"
    dir="{{ $general_settings?->direction ?? 'lrt' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png"
        href="{{ $general_settings?->favicon?->file ?? asset('/logo/small_logo.png') }}" />
    <title>
        {{ $general_settings?->site_title ?? 'Ready POS' }}
        - @yield('title')</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jpreview.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100..900&display=swap" rel="stylesheet">

    <!-- Font-Awesome--Min-Css-Link -->
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    {{-- multiple select css --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- toastr css-->
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}" type="text/css">
    <!-- summerynote css-->
    <link rel="stylesheet" href="{{ asset('assets/summerynote/summernote-bs4.min.css') }}" type="text/css">
    <!-- table sorter stylesheet-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" type="text/css">
    <!--Bootstrap-Icon-Css-Link -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-icons.css') }}">
    <!--ApexChart-Css-Link -->
    <link rel="stylesheet" href="{{ asset('assets/css/apexcharts.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dark-mode.css') }}">
    <!--Responsive--Css-Link -->
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
</head>
<style>
    :root {
        --theme-color: {{ $mainShop?->shopCategory?->primary_color ?? '#37BDF2' }};
        --theme-secondary-color: {{ $mainShop?->shopCategory?->secondary_color ?? '#eaf7fc' }};
        --bs-btn-bg: {{ $mainShop?->shopCategory?->primary_color ?? '#37BDF2' }};
    }

    .has-passport.fixed-header .app-header {
        top: 55px;
    }

    .has-passport.fixed-sidebar .app-main .app-main-outer {
        padding-top: 50px;
    }

    .has-passport.fixed-sidebar .app-sidebar {
        top: 80px;
        height: 100svh;
    }

    .branding-logo-forMobile {
        height: 46px;
    }

    .branding-logo img {
        height: 70px;
    }

    .language-image {
        background: #29aae130;
        padding: 8px 8px 4px 8px;
        border-radius: 4px;
    }

    .language-image div {
        margin-top: 2px font-weight: 800;
    }

    .language-image a img {
        width: 28px;
    }
</style>

<body mode="{{ $general_settings?->dark_mode == 1 ? 'dark' : 'light' }}">
    <div class="w-100 d-flex flex-column gap-1" style="z-index: 99; position: fixed; top: 0;">
        @if ($seederRun)
            <div class="alert alert-danger alert-dismissible fade show mb-0 w-100 text-center rounded-0 text-black"
                role="alert" style="padding: 10px">
                <strong><i class="fa fa-exclamation-circle" data-toggle="tooltip" data-placement="bottom"
                        title='If you do not run this seeder, you will not be able to use the system.'></i>
                    Seeder dose not run.</strong> Please run <code class="text-danger">php artisan migrate:fresh
                    --seed</code> or <a href="{{ route('seeder.run.index') }}" class="btn btn-sm common-btn"> Click
                    here</a>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                    id="closeAlert"></button>
            </div>
        @endif
        @if ($storageLink && config('filesystems.default') == 'public')
            <div class="alert alert-danger alert-dismissible fade show mb-0 w-100 text-center rounded-0 text-black"
                role="alert" style="padding: 10px">
                <strong><i class="fa fa-exclamation-circle" data-toggle="tooltip" data-placement="bottom"
                        title='If you can not install storage link, then image not found.'></i>
                    Storage link dose not exist or image not found then</strong> please run <code class="text-danger">php artisan storage:link</code> or
                    <a href="{{ route('storage.install.index') }}" class="btn btn-sm common-btn"> Click here</a>
                    <br>If you find any problem please set <strong>FILESYSTEM_DISK='local'</strong> in the .env file and try uploading the image again.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                    id="closeAlert"></button>
            </div>
        @endif
    </div>
    <div class="app-container {{ $general_settings?->dark_mode == 1 ? 'app-theme-dark' : 'app-theme-white' }}  body-tabs-shadow fixed-sidebar fixed-header"
        id="appContent">
        <div class="app-header header-shadow">
            <div class="app-header-logo"></div>
            <div class="app-header-mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="app-header-menu">
                <span>
                    <button type="button"
                        class="btn-icon btn-icon-only btn common-btn btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
            </div>
            <div class="app-header-content">
                <!-- Header-left-Section -->
                <div class="app-header-left">
                    <div class="header-pane ">
                        <div>
                            <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                                data-class="closed-sidebar">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- End-Header-Left-section -->

                <!-- Header-Rignt-Section -->
                <div class="app-header-right">
                    @if (isset(auth()->user()->roles[0]->name) && auth()->user()->roles[0]->name != 'super admin')
                        <div class="badgeButtonBox">
                            <div class="notifactionIcon  me-4">
                                <a href="{{ route('sale.pos') }}" class="btn common-btn mr-3 d-block">
                                    <i class="fa-solid fa-cart-plus"></i> {{ __('pos') }}
                                </a>
                            </div>
                        </div>
                    @endif
                    <div class="badgeButtonBox">
                        @if ($general_settings?->dark_mode == 0)
                            <a href="{{ route('settings.theme', 1) }}" class="theme-change-btn">
                                <img src="{{ asset('icons/light.svg') }}" alt="">
                            </a>
                        @else
                            <a href="{{ route('settings.theme', 0) }}" class="theme-change-btn">
                                <img src="{{ asset('icons/dark.svg') }}" alt="">
                            </a>
                        @endif
                    </div>
                    @php
                        use App\Models\Language;
                        $languages = Language::All();
                        $language = Language::where('name', app()->getLocale())->first();
                    @endphp
                    <div class="user-profile-box dropdown mx-3">
                        <div class="nav-profile-box dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="profile-image language-image d-flex gap-2">
                                <a href="#">
                                    <img class="" src="{{ $language->thumbnail->file }}"
                                        alt="{{ $language->title }}">
                                </a>
                                <div>{{ $language->title }}</div>
                            </div>
                        </div>
                        <div class="dropdown-menu profile-item">
                            @foreach ($languages as $lang)
                                <a href="{{ route('change.local', 'ln=' . $lang->name) }}" class="dropdown-item">
                                    <img src="{{ $lang->thumbnail->file }}" alt="{{ $lang->title }}"
                                        width="20" class="me-2"> {{ $lang->title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="user-profile-box dropdown ml-3">
                        <div class="nav-profile-box dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="profile-image">
                                <a href=""><img class="profilepic"
                                        src="{{ auth()->user()->thumbnail->file ?? asset('defualt/defualt.jpg') }}"
                                        alt=""></a>
                            </div>
                            <div class="profile-content">
                                <span>{{ ucfirst(auth()->user()->name) }}</span>
                                <i class="fa-solid fa-angle-down dropIcon"></i>
                            </div>
                        </div>

                        <div class="dropdown-menu profile-item">
                            <a href="{{ route('profile.index', auth()->id()) }}" class="dropdown-item"><i
                                    class="fa fa-user me-2"></i>{{ __('profile') }}</a>
                            <a href="{{ route('settings.general') }}" class="dropdown-item"><i
                                    class="fa fa-cog me-2"></i>{{ __('settings') }}</a>
                            <a href="{{ route('profile.index', auth()->id()) }}" class="dropdown-item"><i
                                    class="fa-solid fa-key me-2"></i>{{ __('change_password') }}</a>

                            <a href="#" onclick="signout()" class="dropdown-item cursor-pointer"><i
                                    class="fa-solid fa-right-from-bracket me-2"></i>{{ __('logout') }}</a>
                        </div>
                    </div>
                </div>
                <!-- End-Header-Right-Section -->

            </div>
        </div>
        <div class="app-main">
            @include('layout.sidebar')

            <!-- ****Body-Section***** -->

            <div class="app-main-outer">
                <!-- ****End-Body-Section**** -->
                <div class="app-main-inner">
                    @yield('content')
                </div>
                <!-- Footer-Section -->
                <div class="app-wrapper-footer">
                    <div class="app-footer">
                        <div class="app-footer-inner">
                            <div>
                                © {{ $general_settings->copyright_text ?? '' }} <a class="razinsoftText"
                                    href="{{ $general_settings->copyright_url ?? '' }}"
                                    target="blank">{{ $general_settings->developed_by ?? '' }}.</a>
                            </div>
                            <div class="d-none d-sm-block">
                                <i class="fa-solid fa-phone"></i>
                                <span>{{ $general_settings->phone ?? '+8801714231625' }}</span>
                            </div>
                            <div class="d-none d-sm-block">
                                <i class="fa-solid fa-envelope mr-1"></i>
                                <span>{{ $general_settings->email ?? 'razinsoftltd@gmail.com' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/scripts/jquery-3.6.3.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/scripts/jquery-ui.min.js') }}"></script>
    <!-- Bootstrap-Min-Bundil-Link -->
    <script src="{{ asset('assets/scripts/bootstrap.bundle.min.js') }}"></script>
    <!-- Main-Script-Js-Link -->
    <script src="{{ asset('assets/scripts/main.js') }}"></script>
    <!--Script-Js-Link -->
    <!-- Full-Screen-Js-Link -->
    <script src="{{ asset('assets/scripts/full-screen.js') }}"></script>
    <!-- Chart-Js-Link -->
    <script type="text/javascript" src="{{ asset('assets/summerynote/summernote-bs4.min.js') }}"></script>
    <!-- Preview image js code -->
    <script src="{{ asset('assets/image_preview_js/bootstrap-prettyfile.js') }}"></script>
    <script src="{{ asset('assets/image_preview_js/jpreview.js') }}"></script>
    <!-- sweetalert js-->
    <script src="{{ asset('assets/scripts/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/sweetalert_modify.js') }}"></script>

    <script type="text/javascript" src="{{ asset('assets/scripts/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/scripts/datatable4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/scripts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/moment.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/daterangepicker.js') }}"></script>
    @stack('scripts')

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

    @if (session('errors'))
        <script>
            Toast.fire({
                icon: 'warning',
                title: "Something went wrong"
            })
        </script>
    @endif
    <script>
        $("#dataTable").dataTable({
            "language": {
                "search": "",
                "searchPlaceholder": "Search..."
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var svgImages = document.querySelectorAll('.menu.active .menu-icon');
            svgImages.forEach(function(svgImage) {
                var svgPath = svgImage.getAttribute('src');
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var svgContent = xhr.responseText;
                        svgContent = svgContent.replace(/stroke="#9395A2"/g,
                            'stroke="{{ $mainShop?->shopCategory?->primary_color ?? '#29aae1' }}"'
                        );
                        svgImage.src = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(
                            svgContent);
                    }
                };
                xhr.open('GET', svgPath, true);
                xhr.send();
            });


            var menus = document.querySelectorAll('.menu');

            menus.forEach(function(menu) {
                menu.addEventListener('mouseover', function() {
                    var svgImages = menu.querySelectorAll('.menu .menu-icon');
                    svgImages.forEach(function(svgImage) {
                        var svgPath = svgImage.getAttribute('src');
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                var svgContent = xhr.responseText;
                                svgContent = svgContent.replace(/stroke="#9395A2"/g,
                                    'stroke="{{ $mainShop?->shopCategory?->primary_color ?? '#29aae1' }}"'
                                );
                                svgImage.src = 'data:image/svg+xml;charset=utf-8,' +
                                    encodeURIComponent(svgContent);
                            }
                        };
                        xhr.open('GET', svgPath, true);
                        xhr.send();
                    });
                });

                menu.addEventListener('mouseout', function() {
                    var svgImages = menu.querySelectorAll('.menu .menu-icon');
                    svgImages.forEach(function(svgImage) {
                        var svgPath = svgImage.getAttribute('src');
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                var svgContent = xhr.responseText;
                                svgContent = svgContent.replace(
                                    /stroke="{{ $mainShop?->shopCategory?->primary_color ?? '#29aae1' }}"/g,
                                    'stroke="#9395A2"');
                                svgImage.src = 'data:image/svg+xml;charset=utf-8,' +
                                    encodeURIComponent(svgContent);
                            }
                        };
                        xhr.open('GET', svgPath, true);
                        xhr.send();
                    });

                    var svgImages = document.querySelectorAll('.menu.active .menu-icon');
                    svgImages.forEach(function(svgImage) {
                        var svgPath = svgImage.getAttribute('src');
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                var svgContent = xhr.responseText;
                                svgContent = svgContent.replace(/stroke="#9395A2"/g,
                                    'stroke="{{ $mainShop?->shopCategory?->primary_color ?? '#29aae1' }}"'
                                );
                                svgImage.src = 'data:image/svg+xml;charset=utf-8,' +
                                    encodeURIComponent(svgContent);
                            }
                        };
                        xhr.open('GET', svgPath, true);
                        xhr.send();
                    });

                    var svgImages = document.querySelectorAll(
                        '.menu[aria-expanded="true"] .menu-icon');
                    svgImages.forEach(function(svgImage) {
                        var svgPath = svgImage.getAttribute('src');
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                var svgContent = xhr.responseText;
                                svgContent = svgContent.replace(/stroke="#9395A2"/g,
                                    'stroke="{{ $mainShop?->shopCategory?->primary_color ?? '#29aae1' }}"'
                                );
                                svgImage.src = 'data:image/svg+xml;charset=utf-8,' +
                                    encodeURIComponent(svgContent);
                            }
                        };
                        xhr.open('GET', svgPath, true);
                        xhr.send();
                    });
                });
            });
        });
        $('#databaseDownloadConfirm').on('click', function(e) {
            e.preventDefault();
            const url = "{{ route('settings.database.backup') }}";
            Swal.fire({
                title: "Are you sure?",
                text: "You wanted to backup your database!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#00B894',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            })
        });
    </script>

    <script>
        $('#closeAlert').on('click', function() {
            $('#appContent').removeClass('has-passport');
        })
    </script>
    @if (session('error'))
        <script>
            Swal.fire({
                title: "Error",
                html: "{!! session('error') !!}",
                icon: "error"
            });
        </script>
    @endif
    <script src="{{ asset('assets/js/main.js') }}" type="text/javascript"></script>
</body>

</html>
