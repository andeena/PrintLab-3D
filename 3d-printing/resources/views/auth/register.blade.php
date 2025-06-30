<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PrintLab 3D</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col justify-center items-center py-6">
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-md w-full">
            <h1 class="text-2xl font-bold text-center text-gray-800">Create Your Account</h1>

            {{-- Optional: A block to display all validation errors at the top --}}
            @if ($errors->any())
                <div class="mt-4 p-3 bg-red-50 text-red-700 border border-red-200 rounded-lg">
                    <p class="font-medium">Oops! Something went wrong.</p>
                    <ul class="list-disc list-inside text-sm mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="mt-6">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Full Name</label>
                    {{-- value="{{ old('name') }}" will keep the user's input if validation fails --}}
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring focus:ring-yellow-300 @error('name') border-red-500 @enderror" required>
                    {{-- The @error directive will display a message if there's a validation error for the 'name' field --}}
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring focus:ring-yellow-300 @error('email') border-red-500 @enderror" required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring focus:ring-yellow-300 @error('password') border-red-500 @enderror" required>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring focus:ring-yellow-300" required>
                    {{-- Error for password_confirmation is usually displayed under the main password field --}}
                </div>
                <button type="submit" class="w-full bg-yellow-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-yellow-400 font-semibold">Register</button>
            </form>
            <p class="mt-4 text-center text-sm text-gray-600">
                Already have an account? <a href="{{ route('login') }}" class="text-yellow-500 hover:underline font-medium">Login here</a>.
            </p>
        </div>
    </div>
</body>
</html>