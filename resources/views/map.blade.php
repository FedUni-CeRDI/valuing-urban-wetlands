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
</head>
<body class="">
<div id="app">

    <nav class="navbar navbar-expand-lg col">
        <div class="container-fluid">
            <h1 class=" col-auto">
                <router-link class="navbar-brand" to="/">

                    <img src="{{ url('storage/logo.svg') }}">
                    {{ config('app.name') }}

                </router-link>
            </h1>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse col justify-content-end" id="navbar">
                <ul class="navbar-nav align-middle">
                    <li class="nav-item">
                        <router-link to="/about" class="nav-link" >About</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/terms" class="nav-link" >Terms of use</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/contact" class="nav-link" >Contact</router-link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="content">
        <map-viewport />
    </div>
</div>
</body>
</html>
