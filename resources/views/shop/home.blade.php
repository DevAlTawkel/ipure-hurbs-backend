@extends('layouts.shop')

@section('title', 'Home')

@section('content')
    <section class="bg-gradient-to-br from-emerald-800 to-emerald-950 text-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <p class="text-sm font-medium uppercase tracking-widest text-emerald-200">Pure Ayurvedic Wellness</p>
            <h1 class="mt-4 max-w-2xl text-4xl font-bold leading-tight sm:text-5xl">
                Natural herbs & supplements for your health
            </h1>
            <p class="mt-4 max-w-xl text-lg text-emerald-100">
                Wide range of authentic products — fresh ingredients, secure checkout, free shipping on orders over $50.
            </p>
            <a href="{{ route('shop.products') }}" class="mt-8 inline-block rounded-lg bg-white px-6 py-3 font-semibold text-emerald-900 hover:bg-emerald-50">
                Shop All Products
            </a>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-stone-900">Shop by Category</h2>
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($categories as $category)
                <a href="{{ route('shop.products', ['category' => $category->slug]) }}"
                   class="rounded-xl border border-stone-200 bg-white p-6 text-center shadow-sm transition hover:border-emerald-300 hover:shadow-md">
                    <h3 class="font-semibold text-emerald-800">{{ $category->name }}</h3>
                    <p class="mt-1 text-sm text-stone-500">Shop now →</p>
                </a>
            @empty
                <p class="col-span-full text-stone-500">Add categories in admin to show them here.</p>
            @endforelse
        </div>
    </section>

    @if ($newProducts->isNotEmpty())
        <section class="bg-white py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-stone-900">New Launch</h2>
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($newProducts as $product)
                        @include('shop.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($trendingProducts->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-stone-900">Trending Products</h2>
            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($trendingProducts as $product)
                    @include('shop.partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>
    @endif

    @if ($featuredProducts->isNotEmpty())
        <section class="bg-emerald-50 py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-stone-900">Featured Products</h2>
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($featuredProducts as $product)
                        @include('shop.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="border-t border-stone-200 bg-white py-12">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:grid-cols-3 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-3xl font-bold text-emerald-800">100%</p>
                <p class="mt-1 text-sm text-stone-600">Ayurvedic Products</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-bold text-emerald-800">Free</p>
                <p class="mt-1 text-sm text-stone-600">Shipping over $50</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-bold text-emerald-800">Secure</p>
                <p class="mt-1 text-sm text-stone-600">Checkout</p>
            </div>
        </div>
    </section>
@endsection
