@extends('layouts.layouts-landing')

@section('title', 'Shopping Cart')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Shopping Cart</h1>
                <a href="/" class="text-emerald-600 hover:text-emerald-700 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Continue Shopping
                </a>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Cart Items -->
                <div class="flex-1">
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden cart-items"
                        data-logged-in="{{ auth()->check() ? 'true' : 'false' }}">
                        <!-- Dynamic cart items will be loaded here -->
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="w-full lg:w-96">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-medium text-gray-900">Order Summary</h2>
                        <dl class="mt-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-600">Subtotal</dt>
                                <dd class="text-sm font-medium text-gray-900" data-summary="subtotal">Rp 0</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-600">Shipping</dt>
                                <dd class="text-sm font-medium text-gray-900" data-summary="shipping">Rp 0</dd>
                            </div>
                            <div class="border-t border-gray-200 pt-4 flex items-center justify-between">
                                <dt class="text-base font-medium text-gray-900">Total</dt>
                                <dd class="text-base font-medium text-gray-900" data-summary="total">Rp 0</dd>
                            </div>
                        </dl>

                        @if (auth()->check())
                            <button data-action="checkout"
                                class="mt-6 w-full bg-emerald-600 text-white py-3 px-4 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                Pay Now
                            </button>
                        @else
                            <div class="flex flex-col gap-6">
                                <a href="{{ route('login') }}"
                                    class="mt-10 w-full bg-emerald-600 text-white py-3 px-4 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 text-center">
                                    Login to Checkout
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
@endpush

{{-- <div class="flex items-center">
    <img src="${item.image}" alt="${$item->name}" class="w-20 h-20 object-cover rounded-lg">
    <div class="ml-4 flex-1">
        <h3 class="text-lg font-medium text-gray-900">${item.name}</h3>
        <p class="mt-1 text-sm text-gray-500">${item.category}</p>
        <div class="mt-2 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <button type="button" class="quantity-btn p-1 rounded-md hover:bg-gray-100" data-action="decrease"
                    data-product-id="${item.id}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                </button>
            </div>
            <span class="font-medium text-gray-900">
                ${this.formatPrice(item.price * item.quantity)}
            </span>
        </div>
    </div>
    <button class="ml-4 text-gray-400 hover:text-red-500" data-action="remove" data-product-id="${item.id}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
    </button>
</div> --}}


{{-- <div class="p-5 text-center text-gray-500">
    <p class="text-2xl">Your cart is empty</p>
    <a href="/" class="text-emerald-600 hover:text-emerald-700">
        Continue Shopping</a>
</div> --}}
