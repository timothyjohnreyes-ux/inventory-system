@extends('layouts.app')

@section('content')

<div class="space-y-8">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="mb-2 text-sm font-semibold uppercase tracking-[0.18em] text-sky-600">Inventory overview</p>
            <h1 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Dashboard</h1>
            <p class="mt-2 text-sm text-slate-500">Overview of your inventory.</p>
        </div>
        <div class="hidden rounded-full bg-sky-50 px-4 py-2 text-sm font-medium text-sky-700 sm:block">
            Stay on top of your stock
        </div>
    </div>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3" aria-label="Inventory summary">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Products</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-slate-950">{{ $totalProducts }}</p>
                </div>
                <div class="rounded-xl bg-sky-50 p-3 text-sky-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7.5 12 3 4 7.5m16 0v9L12 21l-8-4.5v-9m16 0-8 4.5m-8-4.5 8 4.5m0 0V21" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Categories</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-slate-950">{{ $totalCategories }}</p>
                </div>
                <div class="rounded-xl bg-amber-50 p-3 text-amber-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6.5A2.5 2.5 0 0 1 6.5 4h3A2.5 2.5 0 0 1 12 6.5v3A2.5 2.5 0 0 1 9.5 12h-3A2.5 2.5 0 0 1 4 9.5v-3Zm8 8a2.5 2.5 0 0 1 2.5-2.5h3a2.5 2.5 0 0 1 2.5 2.5v3a2.5 2.5 0 0 1-2.5 2.5h-3a2.5 2.5 0 0 1-2.5-2.5v-3Zm0-8A2.5 2.5 0 0 1 14.5 4h3A2.5 2.5 0 0 1 20 6.5v3a2.5 2.5 0 0 1-2.5 2.5h-3A2.5 2.5 0 0 1 12 9.5v-3ZM4 16.5A2.5 2.5 0 0 1 6.5 14h3a2.5 2.5 0 0 1 2.5 2.5v3A2.5 2.5 0 0 1 9.5 22h-3A2.5 2.5 0 0 1 4 19.5v-3Z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Stock</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-slate-950">{{ $totalQuantity }}</p>
                </div>
                <div class="rounded-xl bg-emerald-50 p-3 text-emerald-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 9.5 12 5l7 4.5M5 9.5v7L12 21l7-4.5v-7M5 9.5l7 4.5m7-4.5-7 4.5m0 0V21" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Inventory Value</p>
                    <p class="mt-3 text-2xl font-bold tracking-tight text-slate-950">&#8369;{{ number_format((float) $inventoryValue, 2) }}</p>
                </div>
                <div class="rounded-xl bg-sky-50 p-3 text-sky-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m4-14.5c-.8-.65-2.05-1-3.5-1C10.01 5.5 8 6.62 8 8.25c0 1.64 1.7 2.28 4 2.75 2.3.47 4 1.11 4 2.75S13.99 16.5 11.5 16.5c-1.45 0-2.7-.35-3.5-1" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-amber-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Low Stock</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-amber-600">{{ $lowStockProducts }}</p>
                </div>
                <div class="rounded-xl bg-amber-50 p-3 text-amber-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008M10.29 4.86 3.82 16.5A1.75 1.75 0 0 0 5.34 19h13.32a1.75 1.75 0 0 0 1.52-2.5L13.71 4.86a1.95 1.95 0 0 0-3.42 0Z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-rose-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Out of Stock</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-rose-600">{{ $outOfStockProducts }}</p>
                </div>
                <div class="rounded-xl bg-rose-50 p-3 text-rose-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 8.25 7.5 7.5m0-7.5-7.5 7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-4 border-b border-slate-200 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <h2 class="text-lg font-bold text-slate-950">Recent Products</h2>
                <p class="mt-1 text-sm text-slate-500">The latest items added to your inventory.</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row">
                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                    View All Products
                </a>
                <button
                    type="button"
                    id="openAddProductModal"
                    data-modal-open="addProductModal"
                    class="inline-flex w-fit items-center rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-slate-950 shadow-sm transition hover:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2">
                    Add Product
                </button>
            </div>
        </div>

        @if ($recentProducts->count())
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="whitespace-nowrap px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 sm:px-6">Product Name</th>
                        <th scope="col" class="whitespace-nowrap px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">SKU</th>
                        <th scope="col" class="whitespace-nowrap px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Category</th>
                        <th scope="col" class="whitespace-nowrap px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Quantity</th>
                        <th scope="col" class="whitespace-nowrap px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500 sm:px-6">Price</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach ($recentProducts as $product)
                    <tr class="transition hover:bg-sky-50/50">
                        <td class="whitespace-nowrap px-5 py-4 text-sm font-semibold text-slate-900 sm:px-6">{{ $product->name }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500">{{ $product->sku }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">{{ $product->category->name }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-medium text-slate-700">{{ $product->quantity }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-semibold text-slate-900 sm:px-6">&#8369;{{ number_format((float) $product->price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-6 py-14 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7.5 12 3 4 7.5m16 0v9L12 21l-8-4.5v-9m16 0-8 4.5m-8-4.5 8 4.5m0 0V21" />
                </svg>
            </div>
            <p class="mt-4 text-sm font-medium text-slate-600">No products found.</p>
        </div>
        @endif
    </section>
</div>

{{-- Add Product Modal --}}
<div
    id="addProductModal"
    data-modal
    class="fixed inset-0 z-50 items-center justify-center bg-slate-950/60 p-4 {{ $errors->any() && old('form_type') !== 'edit_product' ? 'flex' : 'hidden' }}">
    <div class="w-full max-w-xl overflow-hidden rounded-xl bg-white shadow-xl">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
            <div>
                <h2 class="text-xl font-bold text-slate-900">
                    Add Product
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Add a new product to your inventory.
                </p>
            </div>

            <button
                type="button"
                id="closeAddProductModal"
                data-modal-close
                class="rounded-lg px-3 py-2 text-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                &times;
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="max-h-[75vh] overflow-y-auto p-6">

            <form
                action="{{ route('products.store') }}"
                method="POST"
                class="space-y-5">

                @csrf
                <input type="hidden" name="form_type" value="add_product">

                {{-- Product Name --}}
                <div>
                    <label
                        class="mb-2 block text-sm font-semibold text-slate-700"
                        for="modal_name">
                        Product Name
                    </label>

                    <input
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-200"
                        type="text"
                        name="name"
                        id="modal_name"
                        value="{{ old('name') }}">

                    @error('name')
                    <p class="mt-2 text-sm font-medium text-rose-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>


                {{-- Category --}}
                <div>
                    <label
                        class="mb-2 block text-sm font-semibold text-slate-700"
                        for="modal_category_id">
                        Category
                    </label>

                    <select
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-200"
                        name="category_id"
                        id="modal_category_id">
                        <option value="">Select Category</option>

                        @foreach($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>

                    @error('category_id')
                    <p class="mt-2 text-sm font-medium text-rose-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Quantity and Price --}}
                <div class="grid gap-5 sm:grid-cols-2">

                    {{-- Quantity --}}
                    <div>
                        <label
                            class="mb-2 block text-sm font-semibold text-slate-700"
                            for="modal_quantity">
                            Quantity
                        </label>

                        <input
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-200"
                            type="number"
                            name="quantity"
                            id="modal_quantity"
                            min="0"
                            value="{{ old('quantity') }}">

                        @error('quantity')
                        <p class="mt-2 text-sm font-medium text-rose-600">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    {{-- Price --}}
                    <div>
                        <label
                            class="mb-2 block text-sm font-semibold text-slate-700"
                            for="modal_price">
                            Price (&#8369;)
                        </label>

                        <input
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-200"
                            type="number"
                            name="price"
                            id="modal_price"
                            min="0"
                            step="0.01"
                            value="{{ old('price') }}">

                        @error('price')
                        <p class="mt-2 text-sm font-medium text-rose-600">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                </div>

                {{-- Description --}}
                <div>
                    <label
                        class="mb-2 block text-sm font-semibold text-slate-700"
                        for="modal_description">
                        Description
                    </label>

                    <textarea
                        class="min-h-32 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-200"
                        name="description"
                        id="modal_description">{{ old('description') }}</textarea>

                    @error('description')
                    <p class="mt-2 text-sm font-medium text-rose-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex justify-end gap-3 border-t border-slate-200 pt-5">

                    <button
                        type="button"
                        id="cancelAddProductModal"
                        data-modal-close
                        class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="rounded-xl bg-amber-500 px-5 py-3 text-sm font-semibold text-slate-950 shadow-sm transition hover:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2">
                        Add Product
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>


@endsection
