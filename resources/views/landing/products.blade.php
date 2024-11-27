<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
    @foreach ($products as $product)
        <div class="product-card bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-72 object-cover">
            <div class="p-4">
                <h3 class="product-name text-lg font-medium text-gray-900">{{ $product->name }}</h3>
                <p class="product-description mt-1 text-sm text-gray-500">{{ $product->description }}</p>
                <div class="mt-4 flex items-center justify-between">
                    <p class="text-lg font-semibold text-emerald-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <button class="add-to-cart px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>
