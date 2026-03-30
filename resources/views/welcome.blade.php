<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Project Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-indigo-600 via-purple-600 to-blue-600 min-h-screen flex items-center justify-center">

    <div class="bg-white rounded-2xl shadow-2xl p-12 max-w-xl w-full text-center">
        
        <h1 class="text-4xl font-bold text-gray-800 mb-4">
            Project Management System
        </h1>

        <p class="text-gray-500 mb-8">
            Manage your teams, projects, and tasks efficiently in one powerful platform.
        </p>

        <div class="flex justify-center gap-4">
            <a href="{{ route('register') }}"
               class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition duration-200 shadow-md">
                Get Started
            </a>

            <a href="{{ route('login') }}"
               class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg font-medium hover:bg-gray-300 transition duration-200">
                Login
            </a>
        </div>

    </div>

</body>
</html>
