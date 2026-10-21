@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div class="min-w-0">
        <a class="mb-2 inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 transition hover:text-sky-700" href="{{ route('products.index') }}">
            <svg aria-hidden="true" class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 12H5m7 7-7-7 7-7" /></svg>
            Back to products
        </a>
        <p class="mb-2 text-xs font-bold uppercase tracking-[0.18em] text-sky-600">Catalog / Product</p>
        <h1 class="wrap-anywhere text-3xl font-bold tracking-tight text-slate-950">{{ $product->name }}</h1>
        <p class="mt-2 wrap-anywhere font-mono text-sm text-slate-500">{{ $product->sku }}</p>
    </div>
    <button type="button" data-modal-open="editProductDetailModal" data-form-action="{{ route('products.update', $product->id) }}" data-name="{{ $product->name }}" data-category-id="{{ $product->category_id }}" data-quantity="{{ $product->quantity }}" data-price="{{ $product->price }}" data-description="{{ $product->description }}" class="inline-flex w-fit items-center gap-2 rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-sky-600/20 transition hover:bg-sky-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 focus-visible:ring-offset-2">
        <svg aria-hidden="true" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m16.862 4.487 1.688-1.688a2.121 2.121 0 0 1 3 3l-9.193 9.193-4.125 1.125 1.125-4.125 7.505-7.505Z" /></svg>
        Edit product
    </button>
</div>

<div class="grid gap-4 sm:grid-cols-2">
    <section class="rounded-2xl border border-sky-100 bg-sky-50 p-5 sm:p-6" aria-label="Product stock">
        <p class="text-sm font-semibold text-sky-800">Available quantity</p>
        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($product->quantity) }}</p>
        <p class="mt-1 text-sm text-slate-600">units in inventory</p>
    </section>
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-label="Product price">
        <p class="text-sm font-semibold text-slate-500">Price per unit</p>
        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">&#8369;{{ number_format((float) $product->price, 2) }}</p>
        <p class="mt-1 text-sm text-slate-500">per unit</p>
    </section>
</div>

<section class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8" aria-labelledby="product-details-heading">
    <h2 id="product-details-heading" class="text-lg font-semibold text-slate-900">Product details</h2>
    <dl class="mt-5 grid gap-x-8 gap-y-5 sm:grid-cols-2">
        <div>
            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Category</dt>
            <dd class="mt-1 text-sm font-medium text-slate-900">
                <a href="{{ route('categories.show', $product->category) }}" class="wrap-anywhere text-sky-700 hover:text-sky-900">{{ $product->category->name }}</a>
            </dd>
        </div>
        <div>
            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">SKU</dt>
            <dd class="mt-1 wrap-anywhere font-mono text-sm text-slate-900">{{ $product->sku }}</dd>
        </div>
        <div class="sm:col-span-2">
            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Description</dt>
            <dd class="mt-1 wrap-anywhere whitespace-pre-line text-sm leading-6 text-slate-700">{{ filled($product->description) ? $product->description : 'No description provided.' }}</dd>
        </div>
    </dl>
</section>

<div id="editProductDetailModal" data-modal class="fixed inset-0 z-50 {{ $errors->any() && old('form_type') === 'edit_product_detail' ? 'flex' : 'hidden' }} items-center justify-center bg-slate-950/60 p-4">
    <div class="w-full max-w-xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/20">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Edit Product</h2>
                <p class="mt-1 text-sm text-slate-500">Update the product details.</p>
            </div>
            <button type="button" data-modal-close aria-label="Close edit product dialog" class="rounded-xl p-2 text-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500">&times;</button>
        </div>

        <form action="{{ route('products.update', $product->id) }}" method="POST" class="max-h-[75vh] space-y-5 overflow-y-auto p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="form_type" value="edit_product_detail">

            <div>
                <label for="detail_product_name" class="mb-1.5 block text-sm font-semibold text-slate-700">Product name</label>
                <input id="detail_product_name" name="name" data-modal-field="name" value="{{ old('name', $product->name) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                @if (old('form_type') === 'edit_product_detail')
                    @error('name') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
                @endif
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="detail_product_sku" class="mb-1.5 block text-sm font-semibold text-slate-700">SKU</label>
                    <input id="detail_product_sku" value="{{ $product->sku }}" readonly class="w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100 px-4 py-2.5 font-mono text-sm text-slate-500">
                </div>
                <div>
                    <label for="detail_product_category" class="mb-1.5 block text-sm font-semibold text-slate-700">Category</label>
                    <select id="detail_product_category" name="category_id" data-modal-field="categoryId" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ (string) old('category_id', $product->category_id) === (string) $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @if (old('form_type') === 'edit_product_detail')
                        @error('category_id') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
                    @endif
                </div>
                <div>
                    <label for="detail_product_quantity" class="mb-1.5 block text-sm font-semibold text-slate-700">Quantity</label>
                    <input id="detail_product_quantity" name="quantity" data-modal-field="quantity" type="number" min="0" value="{{ old('quantity', $product->quantity) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                    @if (old('form_type') === 'edit_product_detail')
                        @error('quantity') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
                    @endif
                </div>
                <div>
                    <label for="detail_product_price" class="mb-1.5 block text-sm font-semibold text-slate-700">Price (&#8369;)</label>
                    <input id="detail_product_price" name="price" data-modal-field="price" type="number" min="0" step="0.01" value="{{ old('price', $product->price) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                    @if (old('form_type') === 'edit_product_detail')
                        @error('price') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
                    @endif
                </div>
            </div>

            <div>
                <label for="detail_product_description" class="mb-1.5 block text-sm font-semibold text-slate-700">Description</label>
                <textarea id="detail_product_description" name="description" data-modal-field="description" class="min-h-28 w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">{{ old('description', $product->description) }}</textarea>
                @if (old('form_type') === 'edit_product_detail')
                    @error('description') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
                @endif
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-200 pt-5">
                <button type="button" data-modal-close class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
