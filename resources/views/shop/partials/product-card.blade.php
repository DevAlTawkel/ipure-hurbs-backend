<article class="group flex flex-col overflow-hidden rounded-xl border border-stone-200 bg-white shadow-sm transition hover:shadow-md">
    <a href="{{ route('shop.product', $product) }}" class="relative aspect-square overflow-hidden bg-stone-100">
        @if ($product->imageUrl())
            <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition group-hover:scale-105">
        @else
            <div class="flex h-full items-center justify-center text-stone-400 text-sm">No image</div>
        @endif
        @if ($product->inStock())
            <span class="absolute left-2 top-2 rounded bg-emerald-600 px-2 py-0.5 text-xs font-medium text-white">In Stock</span>
        @endif
    </a>
    <div class="flex flex-1 flex-col p-4">
        @if ($product->category)
            <p class="text-xs font-medium uppercase tracking-wide text-emerald-700">{{ $product->category->name }}</p>
        @endif
        <h3 class="mt-1 font-semibold text-stone-900 line-clamp-2">
            <a href="{{ route('shop.product', $product) }}" class="hover:text-emerald-700">{{ $product->name }}</a>
        </h3>
        @if ($product->rating > 0)
            <p class="mt-1 text-sm text-amber-600">★ {{ number_format((float) $product->rating, 1) }}</p>
        @endif
        <p class="mt-2 text-lg font-bold text-emerald-800">{{ $product->formattedPrice() }}</p>
        <a href="{{ route('shop.product', $product) }}" class="mt-3 inline-block rounded-lg bg-emerald-700 px-4 py-2 text-center text-sm font-medium text-white hover:bg-emerald-800">
            View Product
        </a>
    </div>
</article>
