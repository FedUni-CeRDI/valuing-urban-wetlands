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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="">

<nav class="navbar navbar-expand-lg col">
    <div class="container-fluid">
        <h1 class=" col-auto">
            <a class="navbar-brand" href="{{ route('home') }}">

                <img src="{{ url('storage/logo.svg') }}">
                {{ config('app.name') }}

            </a>
        </h1>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse col justify-content-end" id="navbar">
            <ul class="navbar-nav align-middle">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about') }}">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('terms') }}">Terms of use</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="content">
    <div class="col-4 sidebar ">
        <h1>
            AURIN-ALA<br>
            Built Environments project
            <span>Valuing Urban Wetlands</span>
        </h1>
        <h2>About</h2>
        <p>
            This project uses citizen science waterbird observation data along with geographic information relevant to land use to demonstrate relative value of wetlands in the Greater Melbourne region.
        </p>
        <p>Urban wetlands collectively support waterbirds and other biodiversity assets. These contribute to healthy "green spaces" in cities. Some individual wetlands will support a greater diversity of species than others. But all wetlands are important and all have value as habitat for flora and fauna, for maintaining water quality, for helping with flood regulation and for human health and wellbeing.</p>
        <p>
            <a href="{{ route('about') }}">Read more..</a>
        </p>

        <h2>How to use this tool</h2>
        <h3>To locate your wetland:</h3>
        <p>
            Enter a wetland name or street address in the "Address search" box OR<br>
            Use the mouse to pan and zoom to the location
        </p>
        <p>
            You can filter wetland types by Land use and by Protected area status
        </p>

        <h3>
            To generate a wetland values report:
        </h3>
        <p>
            Navigate to the wetland of interest and click on wetland to get a report<br>
            The report queries all citizen science records and geographic information within 350m of the wetland edge
        </p>
    </div>
    <div class="col-8 viewport">
        <div id="map"></div>

    </div>
</div>
</body>
</html>
