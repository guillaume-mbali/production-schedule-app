<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>

    <title>Production Schedule Optimization</title>

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>

<body class="antialiased">

    <div class="flex justify-center min-h-screen bg-gray-50">
        <div class="flex flex-col justify-center items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mb-6 w-32 h-32 object-contain">

            <p class="text-lg mb-8 text-gray-600">Laravel Production Schedule Optimization</p>

            <a href="{{ url('/admin/home') }}"
                class="text-center bg-pink-600 hover:bg-pink-800 text-white rounded-sm px-11 py-6 shadow-[rgba(234,_59,_144,_0.2)_10px_10px_0px] transition-all duration-500 w-full mx-auto outline-none border-none">
                Accéder à l'administration
            </a>
        </div>
    </div>

</body>

</html>
