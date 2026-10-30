@extends('layouts.app')


@section('content')
<div class="mb-7 flex min-w-0 flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <p class="mb-2 text-xs font-bold uppercase tracking-[0.18em] text-sky-600">Catalog</p>
        <h1 class="text-3xl font-bold tracking-tight text-slate-950">Products</h1>
        <p class="mt-2 text-sm text-slate-500">Manage product details and keep stock levels up to date.</p>
    </div>
    <button
        type="button"
        id="openAddProductModal"
        data-modal-open="addProductModal"
        class="inline-flex w-fit items-center gap-2 rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-sky-600/20 transition hover:bg-sky-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 focus-visible:ring-offset-2">
        <svg aria-hidden="true" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m-7-7h14" />
        </svg>
        Add product
    </button>
</div>

<section class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="product-filters-heading">

    <div class="mb-5 flex flex-wrap gap-2" role="navigation" aria-label="Product status">
        <a
            href="{{ route('products.index', array_merge(request()->except('page', 'status'), ['status' => 'active'])) }}"
            class="inline-flex items-center rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ $status === 'active' ? 'bg-sky-600 text-white shadow-sm' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">
            Active Products
        </a>

        <a
            href="{{ route('products.index', array_merge(request()->except('page', 'status'), ['status' => 'archived'])) }}"
            class="inline-flex items-center rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ $status === 'archived' ? 'bg-sky-600 text-white shadow-sm' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">
            Archived Products
        </a>
    </div>

    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
        <div>
            <h2 id="product-filters-heading" class="text-base font-semibold text-slate-900">Find products</h2>
            <p class="mt-1 text-sm text-slate-500">Search by name or SKU, or narrow results by category.</p>
        </div>
        <span id="product-result-count" class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
            {{ $products->total() }} {{ \Illuminate\Support\Str::plural('product', $products->total()) }}
        </span>
    </div>

    <form id="product-filters-form" action="{{ route('products.index') }}" method="GET" class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(12rem,0.55fr)_minmax(12rem,0.55fr)_minmax(12rem,0.55fr)_auto] sm:items-end">
        <input type="hidden" name="status" value="{{ $status }}">
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
            <label for="sort" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Sort by</label>

            <select
                name="sort"
                id="sort"
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>
                    Newest First
                </option>

                <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>
                    Oldest First
                </option>

                <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>
                    Name: A–Z
                </option>

                <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>
                    Name: Z–A
                </option>

                <option value="quantity_asc" {{ $sort === 'quantity_asc' ? 'selected' : '' }}>
                    Quantity: Low to High
                </option>

                <option value="quantity_desc" {{ $sort === 'quantity_desc' ? 'selected' : '' }}>
                    Quantity: High to Low
                </option>

                <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>
                    Price: Low to High
                </option>

                <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>
                    Price: High to Low
                </option>
            </select>
        </div>

        <div>
            <label for="stock-status" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Stock level</label>
            <select
                name="stock_status"
                id="stock-status"
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                <option value="all" {{ $stockStatus === 'all' ? 'selected' : '' }}>All stock</option>
                <option value="low_stock" {{ $stockStatus === 'low_stock' ? 'selected' : '' }}>Low stock</option>
                <option value="out_of_stock" {{ $stockStatus === 'out_of_stock' ? 'selected' : '' }}>Out of stock</option>
            </select>
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

        <a href="{{ route('products.index', ['status' => $status, 'sort' => $sort]) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-center text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2">
            Clear
        </a>
    </form>
</section>

<div id="active-product-filters">
    @if (filled($search) || filled($categoryId) || in_array($stockStatus, ['low_stock', 'out_of_stock'], true))
    <div class="mb-4 flex flex-wrap items-center gap-2" aria-label="Active filters">
        <span class="text-sm font-medium text-slate-500">Active filters:</span>
        @if (filled($search))
        <a href="{{ route('products.index', array_filter(['category_id' => $categoryId, 'sort' => $sort, 'status' => $status, 'stock_status' => in_array($stockStatus, ['low_stock', 'out_of_stock'], true) ? $stockStatus : null])) }}" class="inline-flex items-center gap-2 rounded-full bg-sky-50 px-3 py-1.5 text-sm font-medium text-sky-800 ring-1 ring-inset ring-sky-200 hover:bg-sky-100">
            Search: {{ $search }} <span aria-hidden="true" class="text-sky-600">&times;</span>
        </a>
        @endif
        @if ($stockStatus === 'low_stock')
        <a href="{{ route('products.index', array_filter(['search' => $search, 'category_id' => $categoryId, 'sort' => $sort, 'status' => $status])) }}" class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-3 py-1.5 text-sm font-medium text-amber-800 ring-1 ring-inset ring-amber-200 hover:bg-amber-100">
            Low stock <span aria-hidden="true" class="text-amber-600">&times;</span>
        </a>
        @endif
        @if ($stockStatus === 'out_of_stock')
        <a href="{{ route('products.index', array_filter(['search' => $search, 'category_id' => $categoryId, 'sort' => $sort, 'status' => $status])) }}" class="inline-flex items-center gap-2 rounded-full bg-rose-50 px-3 py-1.5 text-sm font-medium text-rose-800 ring-1 ring-inset ring-rose-200 hover:bg-rose-100">
            Out of stock <span aria-hidden="true" class="text-rose-600">&times;</span>
        </a>
        @endif
        @if (filled($categoryId))
        @php($activeCategory = $categories->firstWhere('id', $categoryId))
        @if ($activeCategory)
        <a href="{{ route('products.index', array_filter(['search' => $search, 'sort' => $sort, 'status' => $status])) }}" class="inline-flex items-center gap-2 rounded-full bg-violet-50 px-3 py-1.5 text-sm font-medium text-violet-800 ring-1 ring-inset ring-violet-200 hover:bg-violet-100">
            Category: {{ $activeCategory->name }} <span aria-hidden="true" class="text-violet-600">&times;</span>
        </a>
        @endif
        @endif
        <a href="{{ route('products.index', ['status' => $status, 'sort' => $sort]) }}" class="px-2 py-1.5 text-sm font-semibold text-slate-600 underline decoration-slate-300 underline-offset-4 hover:text-slate-900">Clear all</a>
    </div>
    @endif
</div>

<div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
    <table class="min-w-[860px] w-full divide-y divide-slate-200 text-left text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
            <tr>
                <th scope="col" class="whitespace-nowrap px-4 py-3.5 font-semibold">ID</th>
                <th scope="col" class="whitespace-nowrap px-4 py-3.5 font-semibold">Product</th>
                <th scope="col" class="whitespace-nowrap px-4 py-3.5 font-semibold">SKU</th>
                <th scope="col" class="whitespace-nowrap px-4 py-3.5 font-semibold">Quantity</th>
                <th scope="col" class="whitespace-nowrap px-4 py-3.5 text-right font-semibold">Price</th>
                <th scope="col" class="whitespace-nowrap px-4 py-3.5 font-semibold">Reorder Level</th>
                <th scope="col" class="whitespace-nowrap px-4 py-3.5 font-semibold">Status</th>
                <th scope="col" class="whitespace-nowrap px-4 py-3.5 font-semibold">Actions</th>
            </tr>
        </thead>

        <tbody id="product-results-body">
            @forelse($products as $product)
            <tr class="border-b border-slate-100 transition-colors hover:bg-sky-50/60">
                <td class="whitespace-nowrap px-4 py-3.5 text-xs font-semibold text-slate-400">#{{ $product->id }}</td>
                <td class="max-w-56 px-4 py-3.5 font-semibold text-slate-900" title="{{ $product->name }}">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="truncate">{{ $product->name }}</span>
                        @if (! $product->is_active)
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-slate-600 ring-1 ring-inset ring-slate-200">Archived</span>
                        @endif
                    </div>
                </td>
                <td class="max-w-36 truncate px-4 py-3.5 font-mono text-xs text-slate-500" title="{{ $product->sku }}">{{ $product->sku }}</td>
                <td class="whitespace-nowrap px-4 py-3.5">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-slate-800">{{ number_format($product->quantity) }}</span>
                        @if ((int) $product->quantity === 0)
                        <span class="rounded-full bg-rose-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-rose-700 ring-1 ring-inset ring-rose-200">Out</span>
                        @elseif ($product->quantity <= $product->reorder_level)
                            <span class="rounded-full bg-amber-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-amber-700 ring-1 ring-inset ring-amber-200">Low</span>
                            @endif
                    </div>
                </td>
                <td class="whitespace-nowrap px-4 py-3.5 text-right font-semibold text-slate-900">&#8369;{{ number_format((float) $product->price, 2) }}</td>
                <td class="whitespace-nowrap px-4 py-3.5">
                    <span class="font-semibold text-slate-800">{{ number_format($product->reorder_level) }}</span>
                </td>
                <td class="whitespace-nowrap px-4 py-3.5">
                    @if ($product->is_active)
                    <span class="rounded-full bg-green-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-green-700 ring-1 ring-inset ring-green-200">Active</span>
                    @else
                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-slate-600 ring-1 ring-inset ring-slate-200">Archived</span>
                    @endif
                </td>
                <td class="whitespace-nowrap px-4 py-3.5">
                    <div class="flex items-center gap-1">
                        <a
                            href="{{ route('products.show', $product->id) }}"
                            aria-label="View {{ $product->name }}"
                            title="View product"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-sky-700 transition hover:bg-sky-50 hover:text-sky-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-400">
                            <svg aria-hidden="true" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </a>
                        @if ($product->is_active)
                        <button
                            type="button"
                            data-modal-open="stockInModal"
                            data-product-id="{{ $product->id }}"
                            data-product-name="{{ $product->name }}"
                            data-product-sku="{{ $product->sku }}"
                            data-current-stock="{{ $product->quantity }}"
                            aria-label="Stock in {{ $product->name }}"
                            title="Stock in"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-emerald-600 transition hover:bg-emerald-50 hover:text-emerald-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400">
                            <svg aria-hidden="true" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m-7-7h14" />
                            </svg>
                        </button>
                        @endif
                        @if ($product->quantity > 0)
                        <button
                            type="button"
                            data-modal-open="stockOutModal"
                            data-product-id="{{ $product->id }}"
                            data-product-name="{{ $product->name }}"
                            data-product-sku="{{ $product->sku }}"
                            data-current-stock="{{ $product->quantity }}"
                            aria-label="Stock out {{ $product->name }}"
                            title="Stock out"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-orange-600 transition hover:bg-orange-50 hover:text-orange-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-400">
                            <svg aria-hidden="true" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14" />
                            </svg>
                        </button>
                        @endif
                        <div data-menu class="relative">
                            <button type="button" data-menu-toggle aria-label="More actions for {{ $product->name }}" title="More actions" class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400">
                                <svg aria-hidden="true" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01" />
                                </svg>
                            </button>
                            <div data-menu-content class="fixed z-50 hidden w-40 rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg shadow-slate-900/10">
                                @if ($product->is_active)
                                <button
                                    type="button"
                                    data-modal-open="stockAdjustmentModal"
                                    data-product-id="{{ $product->id }}"
                                    data-product-name="{{ $product->name }}"
                                    data-current-stock="{{ $product->quantity }}"
                                    class="flex w-full items-center rounded-lg px-3 py-2 text-left text-sm font-medium text-slate-700 hover:bg-slate-50">
                                    Adjust stock
                                </button>
                                @endif
                                <button
                                    type="button"
                                    data-modal-open="editProductModal"
                                    data-form-action="{{ route('products.update', $product->id) }}"
                                    data-edit-product-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}"
                                    data-sku="{{ $product->sku }}"
                                    data-category-id="{{ $product->category_id }}"
                                    data-quantity="{{ $product->quantity }}"
                                    data-reorder-level="{{ $product->reorder_level }}"
                                    data-price="{{ $product->price }}"
                                    data-description="{{ $product->description }}"
                                    class="flex w-full items-center rounded-lg px-3 py-2 text-left text-sm font-medium text-slate-700 hover:bg-slate-50">
                                    Edit
                                </button>
                                <form action="{{ route('products.toggle-status', $product->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="flex w-full items-center rounded-lg px-3 py-2 text-left text-sm font-medium {{ $product->is_active ? 'text-amber-700 hover:bg-amber-50' : 'text-emerald-700 hover:bg-emerald-50' }}">
                                        {{ $product->is_active ? 'Archive' : 'Reactivate' }}
                                    </button>
                                </form>
                            </div>
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

{{-- Stock In Modal --}}
<div
    id="stockInModal"
    data-modal
    class="fixed inset-0 z-50 items-center justify-center bg-slate-950/60 p-4 {{ $errors->any() && old('form_type') === 'stock_transaction' && old('type') === 'in' ? 'flex' : 'hidden' }}">
    <div class="w-full max-w-xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/20">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Stock In</h2>
                <p class="mt-1 text-sm text-slate-500">Add stock to the selected product.</p>
            </div>
            <button type="button" data-modal-close class="rounded-xl p-2 text-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500" aria-label="Close stock in modal">&times;</button>
        </div>

        <form action="{{ route('stock-transactions.store') }}" method="POST" class="max-h-[80vh] space-y-5 overflow-y-auto p-6">
            @csrf
            <input type="hidden" name="form_type" value="stock_transaction">
            <input type="hidden" name="product_id" data-modal-field="productId" value="{{ old('form_type') === 'stock_transaction' && old('type') === 'in' ? old('product_id') : '' }}">
            <input type="hidden" name="product_name" value="{{ old('form_type') === 'stock_transaction' && old('type') === 'in' ? old('product_name') : '' }}">
            <input type="hidden" name="product_sku" value="{{ old('form_type') === 'stock_transaction' && old('type') === 'in' ? old('product_sku') : '' }}">
            <input type="hidden" name="current_stock" value="{{ old('form_type') === 'stock_transaction' && old('type') === 'in' ? old('current_stock') : '' }}">
            <input type="hidden" name="return_status" value="{{ $status }}">
            <input type="hidden" name="type" value="in">

            <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                <p class="text-sm font-semibold text-emerald-900" data-modal-field="productName">{{ old('form_type') === 'stock_transaction' && old('type') === 'in' ? old('product_name') : '' }}</p>
                <p class="mt-1 text-xs text-emerald-700">SKU: <span data-modal-field="productSku">{{ old('form_type') === 'stock_transaction' && old('type') === 'in' ? old('product_sku') : '' }}</span></p>
                <p class="mt-2 text-sm text-emerald-800">Current stock: <span class="font-semibold" data-modal-field="currentStock">{{ old('form_type') === 'stock_transaction' && old('type') === 'in' ? old('current_stock') : '' }}</span></p>
            </div>

            <div>
                <label for="stock_in_quantity" class="mb-2 block text-sm font-semibold text-slate-700">Quantity to add</label>
                <input id="stock_in_quantity" name="quantity" type="number" min="1" required value="{{ old('form_type') === 'stock_transaction' && old('type') === 'in' ? old('quantity') : '' }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                @if (old('form_type') === 'stock_transaction' && old('type') === 'in')
                @error('quantity') <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                @endif
            </div>

            <div>
                <label for="stock_in_remarks" class="mb-2 block text-sm font-semibold text-slate-700">Remarks <span class="font-normal text-slate-400">(optional)</span></label>
                <textarea
                    id="stock_in_remarks"
                    name="remarks"
                    maxlength="1000"
                    rows="3"
                    placeholder="Add a note about this transaction"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-2 focus:ring-sky-100">{{ old('form_type') === 'stock_transaction' && old('type') === 'in' ? old('remarks') : '' }}</textarea>
                @if (old('form_type') === 'stock_transaction' && old('type') === 'in')
                @error('remarks') <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                @endif
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-200 pt-5">
                <button type="button" data-modal-close class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Cancel</button>
                <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">Record Stock In</button>
            </div>
        </form>
    </div>
</div>

{{-- Stock Out Modal --}}
<div
    id="stockOutModal"
    data-modal
    class="fixed inset-0 z-50 items-center justify-center bg-slate-950/60 p-4 {{ $errors->any() && old('form_type') === 'stock_transaction' && old('type') === 'out' ? 'flex' : 'hidden' }}">
    <div class="w-full max-w-xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/20">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Stock Out</h2>
                <p class="mt-1 text-sm text-slate-500">Remove stock from the selected product.</p>
            </div>
            <button type="button" data-modal-close class="rounded-xl p-2 text-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-500" aria-label="Close stock out modal">&times;</button>
        </div>

        <form action="{{ route('stock-transactions.store') }}" method="POST" class="max-h-[80vh] space-y-5 overflow-y-auto p-6">
            @csrf
            <input type="hidden" name="form_type" value="stock_transaction">
            <input type="hidden" name="product_id" data-modal-field="productId" value="{{ old('form_type') === 'stock_transaction' && old('type') === 'out' ? old('product_id') : '' }}">
            <input type="hidden" name="product_name" value="{{ old('form_type') === 'stock_transaction' && old('type') === 'out' ? old('product_name') : '' }}">
            <input type="hidden" name="product_sku" value="{{ old('form_type') === 'stock_transaction' && old('type') === 'out' ? old('product_sku') : '' }}">
            <input type="hidden" name="current_stock" value="{{ old('form_type') === 'stock_transaction' && old('type') === 'out' ? old('current_stock') : '' }}">
            <input type="hidden" name="return_status" value="{{ $status }}">
            <input type="hidden" name="type" value="out">

            <div class="rounded-xl border border-orange-100 bg-orange-50 p-4">
                <p class="text-sm font-semibold text-orange-900" data-modal-field="productName">{{ old('form_type') === 'stock_transaction' && old('type') === 'out' ? old('product_name') : '' }}</p>
                <p class="mt-1 text-xs text-orange-700">SKU: <span data-modal-field="productSku">{{ old('form_type') === 'stock_transaction' && old('type') === 'out' ? old('product_sku') : '' }}</span></p>
                <p class="mt-2 text-sm text-orange-800">Available stock: <span class="font-semibold" data-modal-field="currentStock">{{ old('form_type') === 'stock_transaction' && old('type') === 'out' ? old('current_stock') : '' }}</span></p>
            </div>

            <div>
                <label for="stock_out_quantity" class="mb-2 block text-sm font-semibold text-slate-700">Quantity to remove</label>
                <input id="stock_out_quantity" name="quantity" type="number" min="1" max="{{ old('form_type') === 'stock_transaction' && old('type') === 'out' ? old('current_stock') : '' }}" required value="{{ old('form_type') === 'stock_transaction' && old('type') === 'out' ? old('quantity') : '' }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                @if (old('form_type') === 'stock_transaction' && old('type') === 'out')
                @error('quantity') <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                @endif
            </div>

            <div>
                <label for="stock_out_remarks" class="mb-2 block text-sm font-semibold text-slate-700">Remarks <span class="font-normal text-slate-400">(optional)</span></label>
                <textarea id="stock_out_remarks" name="remarks" maxlength="1000" rows="3" placeholder="Add a note about this transaction" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-2 focus:ring-sky-100">{{ old('form_type') === 'stock_transaction' && old('type') === 'out' ? old('remarks') : '' }}</textarea>
                @if (old('form_type') === 'stock_transaction' && old('type') === 'out')
                @error('remarks') <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                @endif
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-200 pt-5">
                <button type="button" data-modal-close class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Cancel</button>
                <button type="submit" class="rounded-xl bg-orange-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">Record Stock Out</button>
            </div>
        </form>
    </div>
</div>

{{-- Stock Adjustment Modal --}}
<div
    id="stockAdjustmentModal"
    data-modal
    class="fixed inset-0 z-50 items-center justify-center bg-slate-950/60 p-4 {{ $errors->any() && old('form_type') === 'stock_adjustment' ? 'flex' : 'hidden' }}">

    <div class="w-full max-w-xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/20">

        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Stock Adjustment</h2>
                <p class="mt-1 text-sm text-slate-500">Set the actual quantity after a physical count or stock discrepancy.</p>
            </div>
            <button type="button" data-modal-close class="rounded-xl p-2 text-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500" aria-label="Close stock adjustment dialog">&times;</button>
        </div>

        <form action="{{ route('stock-adjustments.store') }}" method="POST" class="max-h-[80vh] space-y-5 overflow-y-auto p-6">
            @csrf

            <input type="hidden" name="form_type" value="stock_adjustment">

            {{-- Product ID --}}
            <input
                type="hidden"
                name="product_id"
                id="adjustment_product_id"
                data-modal-field="productId"
                value="{{ old('form_type') === 'stock_adjustment' ? old('product_id') : '' }}">

            <div>
                <label for="adjustment_product_name" class="mb-2 block text-sm font-semibold text-slate-700">Product</label>
                <input
                    type="text"
                    id="adjustment_product_name"
                    name="product_name"
                    readonly
                    data-modal-field="productName"
                    value="{{ old('form_type') === 'stock_adjustment' ? old('product_name') : '' }}"
                    class="w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-500">
            </div>

            <div>
                <label for="adjustment_current_stock" class="mb-2 block text-sm font-semibold text-slate-700">Current Stock</label>
                <input
                    type="number"
                    id="adjustment_current_stock"
                    name="current_stock"
                    readonly
                    data-modal-field="currentStock"
                    value="{{ old('form_type') === 'stock_adjustment' ? old('current_stock') : '' }}"
                    class="w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-500">
            </div>

            <div>
                <label for="adjustment_new_quantity" class="mb-2 block text-sm font-semibold text-slate-700">New Quantity <span class="text-rose-500">*</span></label>
                <input
                    id="adjustment_new_quantity"
                    type="number"
                    name="new_quantity"
                    min="0"
                    required
                    value="{{ old('form_type') === 'stock_adjustment' ? old('new_quantity') : '' }}"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                @if (old('form_type') === 'stock_adjustment')
                @error('new_quantity') <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                @endif
            </div>

            <div>
                <label for="adjustment_reason" class="mb-2 block text-sm font-semibold text-slate-700">Reason <span class="text-rose-500">*</span></label>
                <select id="adjustment_reason" name="reason" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                    <option value="">Select Reason</option>
                    @foreach (['Physical Count', 'Damaged', 'Expired', 'Missing', 'Other'] as $reason)
                    <option value="{{ $reason }}" {{ old('reason') === $reason ? 'selected' : '' }}>{{ $reason }}</option>
                    @endforeach
                </select>
                @if (old('form_type') === 'stock_adjustment')
                @error('reason') <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                @endif
            </div>

            <div>
                <label for="adjustment_remarks" class="mb-2 block text-sm font-semibold text-slate-700">Remarks <span class="font-normal text-slate-400">(optional)</span></label>
                <textarea
                    id="adjustment_remarks"
                    name="remarks"
                    maxlength="1000"
                    rows="3"
                    placeholder="Add a note about this adjustment"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-2 focus:ring-sky-100">{{ old('form_type') === 'stock_adjustment' ? old('remarks') : '' }}</textarea>
                @if (old('form_type') === 'stock_adjustment')
                @error('remarks') <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                @endif
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-200 pt-5">
                <button type="button" data-modal-close class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    Cancel
                </button>

                <button type="submit" class="rounded-xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                    Save Adjustment
                </button>
            </div>

        </form>
    </div>
</div>

{{-- Add Product Modal --}}
<div
    id="addProductModal"
    data-modal
    class="fixed inset-0 z-50 items-center justify-center bg-slate-950/60 p-4 {{ $errors->any() && old('form_type') !== 'edit_product' && old('form_type') !== 'stock_transaction' ? 'flex' : 'hidden' }}">
    <div class="w-full max-w-xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/20">

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
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
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
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
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
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
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

                    {{-- Reorder Level --}}
                    <div>
                        <label
                            class="mb-2 block text-sm font-semibold text-slate-700"
                            for="modal_reorder_level">
                            Reorder Level <span class="text-rose-500">*</span>
                        </label>

                        <input
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                            type="number"
                            name="reorder_level"
                            id="modal_reorder_level"
                            min="0"
                            value="{{ old('reorder_level', 10) }}"
                            required>

                        @error('reorder_level')
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
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
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
                        class="min-h-32 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
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
                        class="rounded-xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 focus-visible:ring-offset-2">
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
    <div class="w-full max-w-xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/20">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Edit Product</h2>
                <p class="mt-1 text-sm text-slate-500">Update product details.</p>
            </div>
            <button type="button" data-modal-close aria-label="Close edit product dialog" class="rounded-xl p-2 text-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500">&times;</button>
        </div>

        <form action="{{ old('edit_product_id') ? route('products.update', old('edit_product_id')) : '#' }}" method="POST" class="max-h-[80vh] space-y-4 overflow-y-auto p-5">
            @csrf
            @method('PUT')
            <input type="hidden" name="form_type" value="edit_product">
            <input type="hidden" name="edit_product_id" value="{{ old('edit_product_id') }}" data-modal-field="editProductId">

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="edit_product_name">Product Name</label>
                <input data-modal-field="name" id="edit_product_name" name="name" value="{{ old('name') }}" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
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
                            class="w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 font-mono text-sm text-slate-500">
                        <span id="edit-product-sku-tooltip" role="tooltip" class="pointer-events-none absolute bottom-full left-0 z-10 mb-2 w-max max-w-64 rounded-lg bg-slate-900 px-3 py-2 text-xs font-medium text-white opacity-0 shadow-lg transition-opacity duration-75 group-hover:opacity-100 group-focus-within:opacity-100">
                            SKU is automatically generated and cannot be changed.
                        </span>
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="edit_product_category_id">Category</label>
                    <select data-modal-field="categoryId" id="edit_product_category_id" name="category_id" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
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
                    <div class="group relative">
                        <input data-modal-field="quantity" id="edit_product_quantity" type="number" min="0" value="{{ old('quantity', $products->getCollection()->firstWhere('id', old('edit_product_id'))?->quantity ?? '') }}" readonly aria-describedby="edit-product-quantity-tooltip" class="w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-500">
                        <span id="edit-product-quantity-tooltip" role="tooltip" class="pointer-events-none absolute bottom-full left-0 z-10 mb-2 w-max max-w-64 rounded-lg bg-slate-900 px-3 py-2 text-xs font-medium text-white opacity-0 shadow-lg transition-opacity duration-75 group-hover:opacity-100 group-focus-within:opacity-100">
                            Quantity is managed through stock in and stock out transactions.
                        </span>
                    </div>

                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="edit_product_reorder_level">Reorder Level <span class="text-rose-500">*</span></label>
                    <input data-modal-field="reorderLevel" id="edit_product_reorder_level" name="reorder_level" type="number" min="0" value="{{ old('reorder_level') }}" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                    @if(old('form_type') === 'edit_product')
                    @error('reorder_level') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    @endif
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="edit_product_price">Price (&#8369;)</label>
                    <input data-modal-field="price" id="edit_product_price" name="price" type="number" min="0" step="0.01" value="{{ old('price') }}" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                    @if(old('form_type') === 'edit_product')
                    @error('price') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    @endif
                </div>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="edit_product_description">Description</label>
                <textarea data-modal-field="description" id="edit_product_description" name="description" class="min-h-24 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">{{ old('description') }}</textarea>
                @if(old('form_type') === 'edit_product')
                @error('description') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                @endif
            </div>
            <div class="flex justify-end gap-2 border-t border-slate-200 pt-4">
                <button type="button" data-modal-close class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700">Save changes</button>
            </div>
        </form>
    </div>
</div>
@endsection