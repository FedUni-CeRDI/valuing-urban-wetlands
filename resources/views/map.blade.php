<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Kurenaido&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/scss/app.scss', 'resources/js/app.js', 'resources/js/helper.js'])

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('aurin.google_analytics_key') }}"></script>
    <script>

        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ config('aurin.google_analytics_key') }}');
    </script>
</head>
<body class="">
<div id="app">

    <nav class="navbar navbar-expand-lg col">
            <div class="col-auto">
                <router-link class="navbar-brand main-heading" to="/">
                    <img src="{{ url('storage/logo.svg') }}">
                    {{ config('app.name') }}
                </router-link>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse col justify-content-end" id="navbar">
                <ul class="navbar-nav align-middle">
                    <li class="nav-item">
                        <router-link to="/about" class="nav-link" onclick="openPanel()">About</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/terms" class="nav-link" onclick="openPanel()">Terms of use</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/contact" class="nav-link" onclick="openPanel()">Contact</router-link>
                    </li>
                    <li class="nav-item" >
                        <router-link to="" class="nav-link d-lg-none" id="filter-link" onclick="toggleFilter()">View Map Filters</router-link>
                    </li>
                </ul>
            </div>
    </nav>
    <div class="content">
        <map-viewport></map-viewport>
    </div>
</div>
</body>
</html>
