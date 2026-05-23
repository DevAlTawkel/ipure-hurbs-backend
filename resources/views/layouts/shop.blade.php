<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Shop') — {{ config('app.name', 'iPureHerbs') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="min-h-screen bg-stone-50 text-stone-900 antialiased">
    <div class="bg-emerald-800 text-white text-center text-sm py-2 px-4">
        Free shipping on orders over ₹599 · 100% Ayurvedic · Secure checkout
    </div>
    <header class="sticky top-0 z-50 border-b border-stone-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('shop.home') }}" class="text-xl font-bold tracking-tight text-emerald-800">
                {{ config('app.name', 'iPureHerbs') }}
            </a>
            <nav class="hidden items-center gap-6 text-sm font-medium text-stone-600 md:flex">
                <a href="{{ route('shop.home') }}" class="hover:text-emerald-700">Home</a>
                <a href="{{ route('shop.products') }}" class="hover:text-emerald-700">All Products</a>
                <a href="/ancy" class="hover:text-emerald-700">Admin</a>
            </nav>
            <form action="{{ route('shop.products') }}" method="get" class="flex w-full max-w-md flex-1 md:w-auto">
                <input
                    type="search"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search herbs & supplements..."
                    class="w-full rounded-l-lg border border-stone-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                >
                <button type="submit" class="rounded-r-lg bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">
                    Search
                </button>
            </form>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="mt-16 border-t border-stone-200 bg-emerald-900 text-emerald-50">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid gap-8 md:grid-cols-3">
                <div>
                    <p class="text-lg font-semibold">{{ config('app.name', 'iPureHerbs') }}</p>
                    <p class="mt-2 text-sm text-emerald-100">Pure Ayurvedic wellness — inspired by trusted herbal brands.</p>
                </div>
                <div>
                    <p class="font-medium">Shop</p>
                    <ul class="mt-2 space-y-1 text-sm text-emerald-100">
                        <li><a href="{{ route('shop.products') }}" class="hover:text-white">All Products</a></li>
                        <li><a href="{{ route('shop.products', ['category' => 'men-wellness']) }}" class="hover:text-white">Men's Wellness</a></li>
                        <li><a href="{{ route('shop.products', ['category' => 'women-wellness']) }}" class="hover:text-white">Women's Wellness</a></li>
                    </ul>
                </div>
                <div>
                    <p class="font-medium">Support</p>
                    <p class="mt-2 text-sm text-emerald-100">WhatsApp · Free shipping ₹599+ · Secure payment</p>
                </div>
            </div>
            <p class="mt-8 border-t border-emerald-800 pt-6 text-center text-xs text-emerald-200">
                &copy; {{ date('Y') }} {{ config('app.name', 'iPureHerbs') }}. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>
