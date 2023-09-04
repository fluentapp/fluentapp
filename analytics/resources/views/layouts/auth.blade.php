<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="title" content="Fluent: Lightweight Web Analytics Tool">
    <meta name="description" content="Utilize Fluent, it ensure that all data from your website remains entirely under your ownership, and the privacy of your site's visitors  is carefully maintained.">
    <meta name="keywords" content="Fluent, lightweight, web analytics tool, website data, privacy, visitors">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Fluent Analytics') }} | @yield('title')</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('images/icon/favicon-32x32.png')}}">
    <link rel="apple-touch-icon" href="{{asset('images/icon/apple-touch-icon.png')}}">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <style>
        body {
            background-color: #f1f1f1 !important;
        }

        .btn-color {
            background: linear-gradient(to right, #DA22FF, #9733EE);
            color: #fff !important;
        }

        .profile-image-pic {
            height: 200px;
            width: 200px;
            object-fit: cover;
        }

        a {
            text-decoration: none;
        }
    </style>
    <!-- Scripts -->
    <script id="fluentanalytics-js" defer data-api="{{ env('DATA_API_FOR_TRACKER') }}" data-domain="{{ env('DATA_DOMAIN_FOR_TRACKER') }}" src="{{ env('SRC_JS_TRACKER') }}"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="text-center mt-3">
                    <img src="{{asset('images/logo.png')}}" class="img-fluid my-3" width="200px" alt="profile">
                </div>
                @yield('content')
            </div>
            <div class="text-center text-xs p-3 mt-3">
                Copyright ©{{date('Y')}} Fluent Analytics All Rights Reserved.
            </div>
        </div>
    </div>
</body>

</html>