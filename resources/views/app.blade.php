<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    @vite('resources/js/app.js')
    @inertiaHead
</head>
<body class="bg-gray-50 py-5">
<div class="max-w-screen-xl mx-auto">
    @inertia
</div>
</body>
</html>
