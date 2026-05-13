<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <link rel="icon" type="image/png"
        href="{{ isset($general_settings->favicon->file) && $general_settings->favicon->file ? $general_settings->favicon->file : asset('/logo/small_logo.png') }}" />
    <title>
        {{ isset($general_settings->site_title) && $general_settings->site_title ? $general_settings->site_title : 'Ready POS' }}
        - @yield('title')</title>
</head>

<body>
    <div id="app">
        @yield('content')
    </div>
    @vite('resources/js/app.js')
</body>

</html>
