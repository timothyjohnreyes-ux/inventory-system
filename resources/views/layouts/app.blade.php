@php
    $currentSection = match (true) {
        request()->routeIs('dashboard') => 'Dashboard',
        request()->routeIs('products.*') => 'Products',
        request()->routeIs('categories.*') => 'Categories',
        request()->routeIs('stock-transactions.*') => 'Stock History',
        request()->routeIs('activity-logs.*') => 'Activity Log',
        default => 'Inventory',
    };
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#f8fafc">
    <title>{{ $title ?? $currentSection.' | TIM Inventory' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen overflow-x-hidden bg-slate-50 text-slate-800 antialiased">
    <div class="flex min-h-screen flex-col lg:flex-row">
        <aside class="relative z-40 w-full shrink-0 border-b border-slate-200 bg-white lg:sticky lg:top-0 lg:flex lg:h-screen lg:w-64 lg:flex-col lg:border-b-0 lg:border-r">
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-5 py-5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-sky-500 lg:px-6 lg:py-6">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-sky-600 text-white shadow-sm shadow-sky-600/25">
                        <svg aria-hidden="true" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3Zm0 0v9m8-4.5-8 4.5m-8-4.5 8 4.5m0 0V21" />
                        </svg>
                    </span>
                    <span class="min-w-0">
                        <span class="block truncate text-lg font-bold tracking-tight text-slate-950"><span class="text-sky-600">TIM</span> Inventory</span>
                        <span class="mt-0.5 block text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">Stock management</span>
                    </span>
                </a>

                <button type="button" class="mr-4 inline-flex h-11 w-11 items-center justify-center rounded-xl text-slate-600 transition hover:bg-slate-100 hover:text-slate-950 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 lg:hidden" aria-label="Open navigation menu" aria-expanded="false" aria-controls="main-navigation" data-mobile-nav-toggle>
                    <svg aria-hidden="true" class="h-6 w-6" data-menu-open-icon fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg aria-hidden="true" class="hidden h-6 w-6" data-menu-close-icon fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m6 6 12 12M18 6 6 18" />
                    </svg>
                </button>
            </div>

            <nav id="main-navigation" class="pointer-events-none absolute inset-x-0 top-full z-50 flex max-h-[80vh] -translate-y-2 flex-col gap-1 overflow-y-auto border-t border-slate-100 bg-white px-4 py-4 opacity-0 shadow-xl transition-[opacity,transform] duration-200 ease-out lg:pointer-events-auto lg:static lg:z-auto lg:max-h-none lg:flex-1 lg:translate-y-0 lg:space-y-1.5 lg:overflow-y-auto lg:border-t-0 lg:px-4 lg:py-5 lg:opacity-100 lg:shadow-none" aria-label="Main navigation" aria-hidden="true" inert>
                <a href="{{ route('dashboard') }}" @class([
                    'group flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 focus-visible:ring-offset-2',
                    'bg-sky-600 text-white shadow-sm shadow-sky-600/20' => request()->routeIs('dashboard'),
                    'text-slate-600 hover:bg-slate-100 hover:text-slate-950' => ! request()->routeIs('dashboard'),
                ]) @if (request()->routeIs('dashboard')) aria-current="page" @endif>
                    <svg aria-hidden="true" class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 13.5h7.5V3.75h-7.5v9.75Zm9 6.75h7.5V10.5h-7.5v9.75Zm0-12h7.5V3.75h-7.5v4.5Zm-9 12h7.5v-4.5h-7.5v4.5Z" />
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('products.index') }}" @class([
                    'group flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 focus-visible:ring-offset-2',
                    'bg-sky-600 text-white shadow-sm shadow-sky-600/20' => request()->routeIs('products.*'),
                    'text-slate-600 hover:bg-slate-100 hover:text-slate-950' => ! request()->routeIs('products.*'),
                ]) @if (request()->routeIs('products.*')) aria-current="page" @endif>
                    <svg aria-hidden="true" class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3Zm0 0v9m8-4.5-8 4.5m-8-4.5 8 4.5m0 0V21" />
                    </svg>
                    Products
                </a>

                <a href="{{ route('categories.index') }}" @class([
                    'group flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 focus-visible:ring-offset-2',
                    'bg-sky-600 text-white shadow-sm shadow-sky-600/20' => request()->routeIs('categories.*'),
                    'text-slate-600 hover:bg-slate-100 hover:text-slate-950' => ! request()->routeIs('categories.*'),
                ]) @if (request()->routeIs('categories.*')) aria-current="page" @endif>
                    <svg aria-hidden="true" class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4.75A1.75 1.75 0 0 1 5.75 3h4.5A1.75 1.75 0 0 1 12 4.75v4.5A1.75 1.75 0 0 1 10.25 11h-4.5A1.75 1.75 0 0 1 4 9.25v-4.5Zm8 10A1.75 1.75 0 0 1 13.75 13h4.5A1.75 1.75 0 0 1 20 14.75v4.5A1.75 1.75 0 0 1 18.25 21h-4.5A1.75 1.75 0 0 1 12 19.25v-4.5Zm0-10A1.75 1.75 0 0 1 13.75 3h4.5A1.75 1.75 0 0 1 20 4.75v4.5A1.75 1.75 0 0 1 18.25 11h-4.5A1.75 1.75 0 0 1 12 9.25v-4.5Zm-8 10A1.75 1.75 0 0 1 5.75 13h4.5A1.75 1.75 0 0 1 12 14.75v4.5A1.75 1.75 0 0 1 10.25 21h-4.5A1.75 1.75 0 0 1 4 19.25v-4.5Z" />
                    </svg>
                    Categories
                </a>

                <a href="{{ route('stock-transactions.index') }}" @class([
                    'group flex w-full items-center justify-between gap-5 rounded-xl px-4 py-3 text-sm font-semibold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 focus-visible:ring-offset-2',
                    'bg-sky-600 text-white shadow-sm shadow-sky-600/20' => request()->routeIs('stock-transactions.*'),
                    'text-slate-600 hover:bg-slate-100 hover:text-slate-950' => ! request()->routeIs('stock-transactions.*'),
                ]) @if (request()->routeIs('stock-transactions.*')) aria-current="page" @endif>
                    <span class="flex items-center gap-3">
                        <svg aria-hidden="true" class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l2.5 1.5M20.25 12a8.25 8.25 0 1 1-2.42-5.83M20.25 4.5v4.75H15.5" />
                        </svg>
                        Stock History
                    </span>
                    @if ($unreadStockTransactionCount > 0)
                        <span class="inline-flex min-w-6 items-center justify-center rounded-full bg-rose-600 px-2 py-1 text-xs font-bold leading-none text-white ring-2 ring-white" aria-label="{{ $unreadStockTransactionCount }} unread transactions">
                            {{ $unreadStockTransactionCount > 99 ? '99+' : $unreadStockTransactionCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('activity-logs.index') }}" @class([
                    'group flex w-full items-center justify-between gap-5 rounded-xl px-4 py-3 text-sm font-semibold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 focus-visible:ring-offset-2',
                    'bg-sky-600 text-white shadow-sm shadow-sky-600/20' => request()->routeIs('activity-logs.*'),
                    'text-slate-600 hover:bg-slate-100 hover:text-slate-950' => ! request()->routeIs('activity-logs.*'),
                ]) @if (request()->routeIs('activity-logs.*')) aria-current="page" @endif>
                    <span class="flex items-center gap-3">
                        <svg aria-hidden="true" class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6.75A2.75 2.75 0 0 1 6.75 4h10.5A2.75 2.75 0 0 1 20 6.75v10.5A2.75 2.75 0 0 1 17.25 20H6.75A2.75 2.75 0 0 1 4 17.25V6.75ZM8 8h8m-8 4h8m-8 4h4" />
                        </svg>
                        Activity Log
                    </span>
                    @if ($unreadActivityLogCount > 0)
                        <span class="inline-flex min-w-6 items-center justify-center rounded-full bg-rose-600 px-2 py-1 text-xs font-bold leading-none text-white ring-2 ring-white" aria-label="{{ $unreadActivityLogCount }} unread activity records">
                            {{ $unreadActivityLogCount > 99 ? '99+' : $unreadActivityLogCount }}
                        </span>
                    @endif
                </a>
            </nav>

            <div class="hidden border-t border-slate-100 p-5 lg:block">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Inventory workspace</p>
                    <p class="mt-2 text-xs leading-5 text-slate-500">Keep products, categories, and stock movements organized in one place.</p>
                </div>
            </div>
        </aside>

        <div class="flex min-h-screen min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/90 shadow-sm shadow-slate-900/[0.02] backdrop-blur-xl">
                <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between gap-4 px-5 sm:px-8">
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">TIM Inventory</p>
                        <p class="truncate text-sm font-semibold text-slate-800">{{ $currentSection }}</p>
                    </div>
                    <div class="hidden items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-600 sm:inline-flex">
                        <span class="h-1.5 w-1.5 rounded-full bg-sky-500"></span>
                        Inventory management
                    </div>
                </div>
            </header>

            <main class="mx-auto w-full min-w-0 max-w-7xl flex-1 overflow-x-hidden px-4 py-6 sm:px-8 sm:py-8">
                @if (session('success'))
                    <div role="status" class="mb-6 flex items-start justify-between gap-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm font-medium text-emerald-900 shadow-sm shadow-emerald-900/[0.03] sm:px-5">
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                                <svg aria-hidden="true" class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 20 20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 10 3 3 7-7" /></svg>
                            </span>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" class="-mr-1 -mt-1 rounded-lg p-1 text-lg leading-none text-emerald-700 transition hover:bg-emerald-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500" aria-label="Dismiss success message" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                @endif

                @if (session('error'))
                    <div role="alert" class="mb-6 flex items-start justify-between gap-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3.5 text-sm font-medium text-rose-900 shadow-sm shadow-rose-900/[0.03] sm:px-5">
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-700">
                                <svg aria-hidden="true" class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 20 20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6v4m0 4h.01M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" /></svg>
                            </span>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" class="-mr-1 -mt-1 rounded-lg p-1 text-lg leading-none text-rose-700 transition hover:bg-rose-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500" aria-label="Dismiss error message" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                @endif

                @if (session('info'))
                    <div role="status" class="mb-6 flex items-start justify-between gap-4 rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3.5 text-sm font-medium text-sky-900 shadow-sm shadow-sky-900/[0.03] sm:px-5">
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-sky-100 text-sky-700">
                                <svg aria-hidden="true" class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 20 20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v5m0-8h.01M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" /></svg>
                            </span>
                            <span>{{ session('info') }}</span>
                        </div>
                        <button type="button" class="-mr-1 -mt-1 rounded-lg p-1 text-lg leading-none text-sky-700 transition hover:bg-sky-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500" aria-label="Dismiss information message" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
