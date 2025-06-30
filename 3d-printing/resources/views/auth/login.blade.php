<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - 3D Design Service</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col justify-center items-center">
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-md w-full">
            <h1 class="text-2xl font-bold text-center text-gray-800">Login to Your Account</h1>
            <form action="{{ route('login') }}" method="POST" class="mt-6">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email Address</label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring focus:ring-yellow-300" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring focus:ring-yellow-300" required>
                </div>
                <div class="mb-6 flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="form-checkbox h-5 w-5 text-yellow-300 rounded">
                        <span class="ml-2 text-gray-700">Remember Me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-yellow-500 hover:underline">Forgot Password?</a>
                </div>
                <button type="submit" class="w-full bg-yellow-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-yellow-400">Login</button>
            </form>
            <p class="mt-4 text-center text-gray-600">Don't have an account? <a href="{{ route('register') }}" class="text-yellow-500 hover:underline">Register here</a>.</p>
        </div>
    </div>
</body>
</html>