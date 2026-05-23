@extends('layouts.shop')

@section('title', 'Products')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-stone-900">All Products</h1>

        <div class="mt-6 flex flex-wrap gap-2">
            <a href="{{ route('shop.products') }}"
               class="rounded-full px-4 py-1.5 text-sm font-medium {{ ! $activeCategory ? 'bg-emerald-700 text-white' : 'bg-stone-200 text-stone-700 hover:bg-stone-300' }}">
                All
            </a>
            @foreach ($categories as $category)
                <a href="{{ route('shop.products', ['category' => $category->slug]) }}"
                   class="rounded-full px-4 py-1.5 text-sm font-medium {{ $activeCategory === $category->slug ? 'bg-emerald-700 text-white' : 'bg-stone-200 text-stone-700 hover:bg-stone-300' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        @if ($products->isEmpty())
            <p class="mt-12 text-center text-stone-500">No products found. Add products in the <a href="/ancy/products" class="text-emerald-700 underline">admin panel</a>.</p>
        @else
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($products as $product)
                    @include('shop.partials.product-card', ['product' => $product])
                @endforeach
            </div>
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
