<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Inventory Management System' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">

    <div class="flex min-h-screen flex-col lg:flex-row">

        {{-- Sidebar --}}
        <aside class="w-full shrink-0 border-b border-slate-200 bg-white text-slate-800 lg:min-h-screen lg:w-72 lg:border-b-0 lg:border-r">

            <div class="border-b border-slate-200 px-6 py-7 lg:px-7">
                <h1 class="text-xl font-bold tracking-tight">
                    <span class="font-bold text-sky-600">TIM</span> Inventory
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Management System
                </p>
            </div>

            <nav class="flex gap-2 overflow-x-auto p-4 lg:block lg:space-y-2 lg:p-5">

                <a
                    href="{{ route('dashboard') }}"
                    @class([
                        'block whitespace-nowrap rounded-xl px-4 py-3 text-sm font-medium transition',
                        'bg-sky-50 text-sky-700 ring-1 ring-inset ring-sky-200' => request()->routeIs('dashboard'),
                        'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => ! request()->routeIs('dashboard'),
                    ])
                    @if (request()->routeIs('dashboard')) aria-current="page" @endif
                >
                    Dashboard
                </a>

                <a
                    href="{{ route('products.index') }}"
                    @class([
                        'block whitespace-nowrap rounded-xl px-4 py-3 text-sm font-medium transition',
                        'bg-sky-50 text-sky-700 ring-1 ring-inset ring-sky-200' => request()->routeIs('products.*'),
                        'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => ! request()->routeIs('products.*'),
                    ])
                    @if (request()->routeIs('products.*')) aria-current="page" @endif
                >
                    Products
                </a>

                <a
                    href="{{ route('categories.index') }}"
                    @class([
                        'block whitespace-nowrap rounded-xl px-4 py-3 text-sm font-medium transition',
                        'bg-sky-50 text-sky-700 ring-1 ring-inset ring-sky-200' => request()->routeIs('categories.*'),
                        'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => ! request()->routeIs('categories.*'),
                    ])
                    @if (request()->routeIs('categories.*')) aria-current="page" @endif
                >
                    Categories
                </a>

            </nav>

        </aside>


        {{-- Main Content --}}
        <div class="min-w-0 flex-1">

            {{-- Top Navigation --}}
            <header class="border-b border-slate-200 bg-white/90 shadow-sm backdrop-blur">

                <div class="px-5 py-5 sm:px-8">

                    <h2 class="text-xl font-bold tracking-tight text-slate-900">
                        {{ $title ?? 'Inventory Management' }}
                    </h2>

                </div>

            </header>


            {{-- Page Content --}}
            <main class="mx-auto w-full min-w-0 max-w-7xl p-5 sm:p-8">

                @if (session('success'))

                    <div class="mb-6 flex items-start justify-between gap-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 shadow-sm">
                        <span>{{ session('success') }}</span>
                        <button type="button" class="-mr-1 -mt-1 rounded-lg p-1 text-lg leading-none text-emerald-700 transition hover:bg-emerald-100 hover:text-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-400" aria-label="Dismiss success message" onclick="this.parentElement.remove()">&times;</button>
                    </div>

                @endif

                @if (session('error'))

                    <div class="mb-6 flex items-start justify-between gap-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800 shadow-sm">
                        <span>{{ session('error') }}</span>
                        <button type="button" class="-mr-1 -mt-1 rounded-lg p-1 text-lg leading-none text-rose-700 transition hover:bg-rose-100 hover:text-rose-900 focus:outline-none focus:ring-2 focus:ring-rose-400" aria-label="Dismiss error message" onclick="this.parentElement.remove()">&times;</button>
                    </div>

                @endif

                @if (session('info'))

                    <div class="mb-6 flex items-start justify-between gap-4 rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm font-medium text-sky-800 shadow-sm">
                        <span>{{ session('info') }}</span>
                        <button type="button" class="-mr-1 -mt-1 rounded-lg p-1 text-lg leading-none text-sky-700 transition hover:bg-sky-100 hover:text-sky-900 focus:outline-none focus:ring-2 focus:ring-sky-400" aria-label="Dismiss information message" onclick="this.parentElement.remove()">&times;</button>
                    </div>

                @endif

                @yield('content')

            </main>

        </div>

    </div>

</body>

</html>
