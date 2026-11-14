<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Warehouse Inventory System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">

    <div class="w-full max-w-md rounded-xl bg-white p-8 shadow-lg">

        <h1 class="mb-2 text-center text-2xl font-bold text-slate-800">
            Warehouse Inventory System
        </h1>

        <p class="mb-6 text-center text-sm text-slate-500">
            Sign in to your account
        </p>

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf


            @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif


            <div class="mb-4">
                <label for="email" class="mb-2 block text-sm font-medium">
                    Email Address
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    autocomplete="email"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2"
                    placeholder="Enter your email">
            </div>

            <div class="mb-6">
                <label for="password" class="mb-2 block text-sm font-medium">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="current-password"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2"
                    placeholder="Enter your password">
            </div>

            <button
                type="submit"
                class="w-full rounded-lg bg-blue-600 py-3 font-semibold text-white hover:bg-blue-700">
                Login
            </button>
        </form>

    </div>

</body>

</html>