<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Something went wrong')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800 flex items-center justify-center h-screen">
<div class="max-w-md w-full   rounded-2xl p-8 text-center">
    <div class="text-6xl font-bold text-blue-500">@yield('code')</div>
    <h1 class="mt-4 text-2xl font-semibold">@yield('title')</h1>
    <p class="mt-2 text-gray-600">@yield('message')</p>

    <a href="{{ url('/') }}"
       class="mt-6 inline-block px-6 py-2 bg-blue-500 text-white rounded-xl hover:bg-orange-600 transition">
        Go Home
    </a>
</div>
</body>
</html>
