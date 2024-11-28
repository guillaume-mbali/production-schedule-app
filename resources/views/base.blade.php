<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Production Schedule Optimization</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@5.5.1/main.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" rel="stylesheet" />

</head>

<body class="bg-gray-100 text-gray-900">

    <div class="min-h-screen flex">
        <aside class="w-64 bg-white shadow-md z-50">
            @include('layouts.sidebar')
        </aside>

        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@5.5.1/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@5.5.1/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@5.5.1/main.min.js"></script>


</body>

</html>
