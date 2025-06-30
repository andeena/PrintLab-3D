<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to PrintLab 3D</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(120deg, #f6d365 0%, #fda085 100%);
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex flex-col justify-center items-center text-gray-800">
        <div class="text-center">
            <h1 class="text-6xl font-extrabold text-white drop-shadow-lg">Welcome to <span class="text-yellow-300">PrintLab 3D</span></h1>
            <p class="mt-4 text-lg text-gray-200">Bringing your ideas to life with cutting-edge 3D technology.</p>
        </div>

        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('register') }}" class="bg-yellow-300 text-gray-800 px-6 py-3 rounded-lg shadow-lg text-xl font-semibold hover:bg-yellow-400">Get Started</a>
            <a href="{{ route('login') }}" class="bg-gray-800 text-white px-6 py-3 rounded-lg shadow-lg text-xl font-semibold hover:bg-gray-700">Login</a>
        </div>

        <div class="absolute bottom-8 text-gray-300">
            <p>&copy; 2025 PrintLab 3D. All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>
