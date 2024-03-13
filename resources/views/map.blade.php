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
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('aurin.google_analytics_key') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        let filterOnFlag=false;
        gtag('config', '{{ config('aurin.google_analytics_key') }}');

        function openPanel(){
            document.getElementById(
                "aurin-sidebar").style.display = "block";
            document.getElementById(
                "map-viewport").className = "viewport";
            document.getElementById(
                "panel-open").style.display = "none";
        }
        function toggleFilter(){
            if(filterOnFlag){
                document.getElementById("filter-link").innerHTML="Hide Map Filters";
                document.getElementById(
                    "landUse").style.display = "block";
                document.getElementById(
                    "landUse-label").style.display = "block";
                document.getElementById(
                    "protectionStatus").style.display = "block";
                document.getElementById(
                    "protectionStatus-label").style.display = "block";
            }
            else {
                document.getElementById("filter-link").innerHTML="View Map Filters";
                document.getElementById(
                    "landUse").style.display = "none";
                document.getElementById(
                    "landUse-label").style.display = "none";
                document.getElementById(
                    "protectionStatus").style.display = "none";
                document.getElementById(
                    "protectionStatus-label").style.display = "none";
            }
            filterOnFlag=!filterOnFlag;
        }
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
                        <router-link to="/" class="nav-link d-lg-none" id="filter-link" onclick="toggleFilter()">View Map Filters</router-link>
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
