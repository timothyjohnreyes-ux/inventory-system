@extends('layouts.app')


@section('content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <h1 class="text-3xl font-bold tracking-tight text-slate-900">Products</h1>





    <button
        type="button"
        id="openAddProductModal"
        data-modal-open="addProductModal"
        class="inline-flex w-fit items-center rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-slate-950 shadow-sm transition hover:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2">
        Add Product
    </button>


</div>

<section class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="product-filters-heading">
    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
        <div>
            <h2 id="product-filters-heading" class="text-base font-semibold text-slate-900">Find products</h2>
            <p class="mt-1 text-sm text-slate-500">Search by name or SKU, or narrow results by category.</p>
        </div>
        <span id="product-result-count" class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
            {{ $products->total() }} {{ \Illuminate\Support\Str::plural('product', $products->total()) }}
        </span>
    </div>

    <form id="product-filters-form" action="{{ route('products.index') }}" method="GET" class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(12rem,0.55fr)_auto] sm:items-end">
        <div>
            <label for="product-search" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Search</label>
            <input
                id="product-search"
                type="search"
                name="search"
                value="{{ $search ?? request('search') }}"
                placeholder="Product name or SKU"
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
        </div>

        <div>
            <label for="product-category" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Category</label>
            <select
                id="product-category"
                name="category_id"
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                <option value="">All categories</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ (string) ($categoryId ?? '') === (string) $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>

        <a href="{{ route('products.index') }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2">
            Clear
        </a>
    </form>
</section>

<div id="active-product-filters">
@if (filled($search) || filled($categoryId))
<div class="mb-4 flex flex-wrap items-center gap-2" aria-label="Active filters">
    <span class="text-sm font-medium text-slate-500">Active filters:</span>
    @if (filled($search))
    <a href="{{ route('products.index', array_filter(['category_id' => $categoryId])) }}" class="inline-flex items-center gap-2 rounded-full bg-sky-50 px-3 py-1.5 text-sm font-medium text-sky-800 ring-1 ring-inset ring-sky-200 hover:bg-sky-100">
        Search: {{ $search }} <span aria-hidden="true" class="text-sky-600">&times;</span>
    </a>
    @endif
    @if (filled($categoryId))
    @php($activeCategory = $categories->firstWhere('id', $categoryId))
    @if ($activeCategory)
    <a href="{{ route('products.index', array_filter(['search' => $search])) }}" class="inline-flex items-center gap-2 rounded-full bg-violet-50 px-3 py-1.5 text-sm font-medium text-violet-800 ring-1 ring-inset ring-violet-200 hover:bg-violet-100">
        Category: {{ $activeCategory->name }} <span aria-hidden="true" class="text-violet-600">&times;</span>
    </a>
    @endif
    @endif
    <a href="{{ route('products.index') }}" class="px-2 py-1.5 text-sm font-semibold text-slate-600 underline decoration-slate-300 underline-offset-4 hover:text-slate-900">Clear all</a>
</div>
@endif
</div>

<div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
    <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
            <tr>
                <th class="whitespace-nowrap px-5 py-4 font-semibold">ID</th>
                <th class="whitespace-nowrap px-5 py-4 font-semibold">Product Name</th>
                <th class="whitespace-nowrap px-5 py-4 font-semibold">SKU</th>
                <th class="whitespace-nowrap px-5 py-4 font-semibold">Category</th>
                <th class="whitespace-nowrap px-5 py-4 font-semibold">Quantity</th>
                <th class="whitespace-nowrap px-5 py-4 text-right font-semibold">Price</th>
                <th class="px-5 py-4 font-semibold">Description</th>
                <th class="whitespace-nowrap px-5 py-4 font-semibold">Actions</th>
            </tr>
        </thead>

        <tbody id="product-results-body">
            @forelse($products as $product)
            <tr class="border-b border-slate-100 transition hover:bg-sky-50">
                <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-500">{{ $product->id }}</td>
                <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-900">{{ $product->name }} </td>
                <td class="whitespace-nowrap px-5 py-4 font-mono text-xs text-slate-600">{{ $product->sku }}</td>
                <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $product->category->name }}</td>
                <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $product->quantity }}</td>
                <td class="whitespace-nowrap px-5 py-4 text-right font-medium text-slate-900">&#8369;{{ number_format((float) $product->price, 2) }}</td>
                <td class="max-w-md px-5 py-4 text-slate-600">{{ $product->description }}</td>
                <td class="px-5 py-4">
                    <div class="relative" data-menu>
                        <button type="button" data-menu-toggle aria-label="Open actions for {{ $product->name }}" class="rounded-md p-2 text-lg leading-none text-slate-500 hover:bg-slate-100 hover:text-slate-900">
                            <span aria-hidden="true">&#8942;</span>
                        </button>
                        <div data-menu-content class="fixed z-50 hidden w-32 rounded-lg border border-slate-200 bg-white p-1 text-sm shadow-lg">
                            <a class="block rounded-md px-3 py-2 text-slate-700 hover:bg-slate-100" href="{{ route('products.show', $product->id) }}">View</a>
                            <button
                                type="button"
                                data-modal-open="editProductModal"
                                data-form-action="{{ route('products.update', $product->id) }}"
                                data-edit-product-id="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-sku="{{ $product->sku }}"
                                data-category-id="{{ $product->category_id }}"
                                data-quantity="{{ $product->quantity }}"
                                data-price="{{ $product->price }}"
                                data-description="{{ $product->description }}"
                                class="block w-full rounded-md px-3 py-2 text-left text-sky-700 hover:bg-sky-50">
                                Edit
                            </button>
                            <button type="button" data-modal-open="deleteProductModal" data-form-action="{{ route('products.destroy', $product->id) }}" data-delete-name="{{ $product->name }}" class="block w-full rounded-md px-3 py-2 text-left text-rose-600 hover:bg-rose-50">Delete</button>
                        </div>
                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-14 text-center">
                    <p class="text-base font-semibold text-slate-900">No products found</p>
                    <p class="mt-1 text-sm text-slate-500">Try a different search or category.</p>
                    <a href="{{ route('products.index') }}" class="mt-4 inline-flex rounded-lg px-3 py-2 text-sm font-semibold text-sky-700 hover:bg-sky-50">Clear filters</a>
                </td>
            </tr>
            @endforelse
        </tbody>

    </table>
</div>

<div id="product-pagination" class="mt-6">
    {{ $products->links() }}
</div>

{{-- Delete Product Modal --}}
<div id="deleteProductModal" data-modal class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4">
    <div class="w-full max-w-sm overflow-hidden rounded-xl bg-white shadow-xl">
        <div class="p-5">
            <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-full bg-rose-100 text-lg font-bold text-rose-600">!</div>
            <h2 class="text-lg font-semibold text-slate-900">Delete product?</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">Are you sure you want to delete <span data-modal-field="deleteName" class="font-semibold text-slate-900"></span>? This action cannot be undone.</p>
        </div>
        <form action="{{ route('products.destroy', 0) }}" method="POST" class="flex justify-end gap-2 border-t border-slate-200 bg-slate-50 px-5 py-4">
            @csrf
            @method('DELETE')
            <button type="button" data-modal-close class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-white">Cancel</button>
            <button type="submit" class="rounded-md bg-rose-600 px-4 py-2 text-sm font-medium text-white hover:bg-rose-700">Delete</button>
        </form>
    </div>
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

{{-- Edit Product Modal --}}
<div
    id="editProductModal"
    data-modal
    class="fixed inset-0 z-50 {{ $errors->any() && old('form_type') === 'edit_product' ? 'flex' : 'hidden' }} items-center justify-center bg-slate-950/60 p-4">
    <div class="w-full max-w-xl overflow-hidden rounded-xl bg-white shadow-xl">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Edit Product</h2>
                <p class="mt-1 text-sm text-slate-500">Update product details.</p>
            </div>
            <button type="button" data-modal-close class="rounded-md px-2 py-1 text-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700">&times;</button>
        </div>

        <form action="{{ old('edit_product_id') ? route('products.update', old('edit_product_id')) : '#' }}" method="POST" class="max-h-[80vh] space-y-4 overflow-y-auto p-5">
            @csrf
            @method('PUT')
            <input type="hidden" name="form_type" value="edit_product">
            <input type="hidden" name="edit_product_id" value="{{ old('edit_product_id') }}" data-modal-field="editProductId">

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="edit_product_name">Product Name</label>
                <input data-modal-field="name" id="edit_product_name" name="name" value="{{ old('name') }}" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500">
                @if(old('form_type') === 'edit_product')
                @error('name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                @endif
            </div>
            <div class="grid gap-4 sm:grid-cols-2">

                <div>
                    <label
                        class="mb-1 block text-sm font-medium text-slate-700"
                        for="edit_product_sku">
                        SKU
                    </label>

                    <div class="group relative">
                        <input
                            data-modal-field="sku"
                            id="edit_product_sku"
                            type="text"
                            value="{{ old('sku') }}"
                            readonly
                            aria-describedby="edit-product-sku-tooltip"
                            class="w-full cursor-not-allowed rounded-md border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-500">
                        <span id="edit-product-sku-tooltip" role="tooltip" class="pointer-events-none absolute bottom-full left-0 z-10 mb-2 w-max max-w-64 rounded-lg bg-slate-900 px-3 py-2 text-xs font-medium text-white opacity-0 shadow-lg transition-opacity duration-75 group-hover:opacity-100 group-focus-within:opacity-100">
                            SKU is automatically generated and cannot be changed.
                        </span>
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="edit_product_category_id">Category</label>
                    <select data-modal-field="categoryId" id="edit_product_category_id" name="category_id" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @if(old('form_type') === 'edit_product')
                    @error('category_id') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    @endif
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="edit_product_quantity">Quantity</label>
                    <input data-modal-field="quantity" id="edit_product_quantity" name="quantity" type="number" min="0" value="{{ old('quantity') }}" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500">
                    @if(old('form_type') === 'edit_product')
                    @error('quantity') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    @endif
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="edit_product_price">Price (&#8369;)</label>
                    <input data-modal-field="price" id="edit_product_price" name="price" type="number" min="0" step="0.01" value="{{ old('price') }}" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500">
                    @if(old('form_type') === 'edit_product')
                    @error('price') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    @endif
                </div>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="edit_product_description">Description</label>
                <textarea data-modal-field="description" id="edit_product_description" name="description" class="min-h-24 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500">{{ old('description') }}</textarea>
                @if(old('form_type') === 'edit_product')
                @error('description') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                @endif
            </div>
            <div class="flex justify-end gap-2 border-t border-slate-200 pt-4">
                <button type="button" data-modal-close class="rounded-md border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
