@extends('layouts.shop')

@section('title', $product->name)

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <nav class="mb-6 text-sm text-stone-500">
            <a href="{{ route('shop.home') }}" class="hover:text-emerald-700">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('shop.products') }}" class="hover:text-emerald-700">Products</a>
            <span class="mx-2">/</span>
            <span class="text-stone-900">{{ $product->name }}</span>
        </nav>

        <div class="grid gap-10 lg:grid-cols-2">
            <div class="aspect-square overflow-hidden rounded-2xl border border-stone-200 bg-stone-100">
                @if ($product->imageUrl())
                    <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                @else
                    <div class="flex h-full items-center justify-center text-stone-400">No image</div>
                @endif
            </div>
            <div>
                @if ($product->category)
                    <p class="text-sm font-medium uppercase text-emerald-700">{{ $product->category->name }}</p>
                @endif
                <h1 class="mt-2 text-3xl font-bold text-stone-900">{{ $product->name }}</h1>
                @if ($product->rating > 0)
                    <p class="mt-2 text-amber-600">★ {{ number_format((float) $product->rating, 1) }} rating</p>
                @endif
                <p class="mt-4 text-3xl font-bold text-emerald-800">{{ $product->formattedPrice() }}</p>
                <p class="mt-2 text-sm {{ $product->inStock() ? 'text-emerald-600' : 'text-red-600' }}">
                    {{ $product->inStock() ? 'In Stock ('.$product->stock.' available)' : 'Out of Stock' }}
                </p>
                @if ($product->description)
                    <div class="mt-6 prose prose-stone max-w-none">
                        <p>{{ $product->description }}</p>
                    </div>
                @endif
                <button type="button" disabled class="mt-8 w-full rounded-lg bg-emerald-700 px-6 py-3 font-semibold text-white opacity-60 cursor-not-allowed sm:w-auto">
                    Add to Cart (coming soon)
                </button>
            </div>
        </div>

        @if ($relatedProducts->isNotEmpty())
            <section class="mt-16">
                <h2 class="text-xl font-bold text-stone-900">Related Products</h2>
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($relatedProducts as $related)
                        @include('shop.partials.product-card', ['product' => $related])
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
