<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="//unpkg.com/element-plus/dist/index.css">
        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
         <link rel="stylesheet" href="{{ mix('/resources/sass/app.scss') }}">
         <link rel="stylesheet" href="{{ mix('/resources/sass/_variables.scss') }}">
    </head>
    <body class="antialiased">
    <div id="app">

    </div>
    <script type="module" src="{{ mix('/resources/js/app.js') }}"></script>
    </body>
</html>
