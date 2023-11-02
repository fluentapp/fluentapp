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

<body class="d-flex flex-column min-vh-100">
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
                                <!-- 
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
                                -->
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

        <main class="py-4 mb-5">
            @yield('content')
        </main>
    </div>
    <!-- Footer -->
    <footer class="mt-auto text-center1 text-lg-start text-white" style="background-color: #313d55">
        <!-- Grid container -->
        <div class="container p-4 pb-0">
            <!-- Section: Links -->
            <section class="">
                <!--Grid row-->
                <div class="row">
                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                        <h6 class="text-uppercase mb-4 font-weight-bold">
                            {{ config('app.name', 'Fluent Analytics') }}
                        </h6>
                        <p>
                            Fluent Analytics is a privacy aware, open-source, cookie free website analytics tool. Unlike Google Analytics we don't sell your data, we don't make your website slower and we don't require any training to understand our metrics.
                        </p>
                    </div>
                    <!-- Grid column -->

                    <hr class="w-100 clearfix d-md-none" />

                    <!-- Grid column -->
                    <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                        <h6 class="text-uppercase mb-4 font-weight-bold">Quick Links</h6>

                        <p>
                            <a href="https://fluentapp.io/blog/" class="text-white text-decoration-none">Blog</a>
                        </p>
                        <p>
                            <a href="https: //fluentapp.io/contact-us/" class="text-white text-decoration-none">Contact Us</a>
                        </p>
                        <p>
                            <a href="https://fluentapp.io/docs/welcome/" class="text-white text-decoration-none">Docs</a>
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <hr class="w-100 clearfix d-md-none" />

                    <!-- Grid column -->
                    <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3 pb-5">
                        <h6 class="text-uppercase mb-4 font-weight-bold">Contact</h6>
                        <a class="text-decoration-none link-light" href="mailto:hello@fluentapp.io"><i class="pi pi-envelope mr-1 text-light"></i> hello@fluentapp.io</a>
                    </div>
                    <!-- Grid column -->

                    <hr class="w-100 clearfix d-md-none" />

                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
                        <h6 class="text-uppercase mb-4 font-weight-bold">Follow us</h6>
                        <!-- Linkedin -->
                        <a class="link-light btn" href="https://www.linkedin.com/company/fluent-analytics-app/" role="button"><i class="pi pi-linkedin text-1white"></i></a>
                        <!-- Twitter -->
                        <a class="link-light btn" href="#!" role="button"><i class="pi pi-twitter text-w1hite"></i></a>
                    </div>
                </div>
                <!--Grid row-->
            </section>
            <!-- Section: Links -->
        </div>
        <!-- Grid container -->

        <!-- Copyright -->
        <div class="text-center text-xs p-3" style="background-color: rgba(0, 0, 0, 0.2)">
            Copyright ©{{date('Y')}} Fluent Analytics All Rights Reserved.
        </div>
        <!-- Copyright -->
    </footer>
    <!-- Footer -->
</body>

</html>