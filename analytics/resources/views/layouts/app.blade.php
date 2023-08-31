<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="title" content="Fluent: Lightweight Web Analytics Tool">
    <meta name="description" content="Utilize Fluent, it ensure that all data from your website remains entirely under your ownership, and the privacy of your site's visitors is carefully maintained.">
    <meta name="keywords" content="Fluent, lightweight, web analytics tool, website data, privacy, visitors">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Fluent Analytics') }} | @yield('title')</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('images/icon/favicon-32x32.png')}}">
    <link rel="apple-touch-icon" href="{{asset('images/icon/apple-touch-icon.png')}}">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    <script id="fluentanalytics-js" defer data-api="{{ env('DATA_API_FOR_TRACKER') }}" data-domain="{{ env('DATA_DOMAIN_FOR_TRACKER') }}" src="{{ env('SRC_JS_TRACKER') }}"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/manage-sites/') }}">
                    <img src="{{asset('images/logo.png')}}" style="max-height: 45px;" /> </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle text-end" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <div class="dropdown-item">
                                    <div class="flex justify-center sm:justify-start sm:pt-0">
                                        @foreach(["عربي" => "ar", "English"=>"en"] as $locale_name => $available_locale)
                                        @if($available_locale === app()->getLocale())
                                        <span class="ml-2 mr-2 text-gray-700">{{ $locale_name }}</span>
                                        @else
                                        <a class="ml-1 underline ml-2 mr-2" href="language/{{ $available_locale }}">
                                            <span>{{ $locale_name }}</span>
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                <a class="dropdown-item" href="{{ route('manage-sites') }}">
                                    {{ __('Manage Sites') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

</html>