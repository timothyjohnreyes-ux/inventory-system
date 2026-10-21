@extends('layouts.app')

@section('content')
<div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <p class="mb-2 text-xs font-bold uppercase tracking-[0.18em] text-sky-600">Catalog</p>
        <h1 class="text-3xl font-bold tracking-tight text-slate-950">Edit product</h1>
        <p class="mt-2 text-sm text-slate-500">Update the details and current inventory for {{ $product->name }}.</p>
    </div>
    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 self-start rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 focus-visible:ring-offset-2 sm:self-auto">
        <svg aria-hidden="true" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 12H5m7 7-7-7 7-7" /></svg>
        Back to products
    </a>
</div>

<form class="max-w-4xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" action="{{ route('products.update', $product->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid gap-5 p-5 sm:grid-cols-2 sm:gap-6 sm:p-7">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700" for="name">Product name <span class="text-rose-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100" required autofocus>
            @error('name')
                <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700" for="category_id">Category <span class="text-rose-500">*</span></label>
            <select class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100" name="category_id" id="category_id" required>
                <option value="">Select a category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
                <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700" for="sku">SKU</label>
            <input type="text" id="sku" value="{{ $product->sku }}" readonly aria-describedby="product-sku-help" class="w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 font-mono text-sm text-slate-500">
            <p id="product-sku-help" class="mt-2 text-xs text-slate-500">This system-generated code cannot be changed.</p>
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700" for="quantity">Quantity <span class="text-rose-500">*</span></label>
            <input type="number" name="quantity" id="quantity" min="0" value="{{ old('quantity', $product->quantity) }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100" required>
            @error('quantity')
                <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700" for="price">Unit price (&#8369;) <span class="text-rose-500">*</span></label>
            <input type="number" name="price" id="price" min="0" step="0.01" value="{{ old('price', $product->price) }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100" required>
            @error('price')
                <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <label class="mb-2 block text-sm font-semibold text-slate-700" for="description">Description <span class="font-normal text-slate-400">(optional)</span></label>
            <textarea class="min-h-32 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" name="description" id="description" placeholder="Add useful details about this product">{{ old('description', $product->description) }}</textarea>
            @error('description')
                <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50/70 px-5 py-4 sm:flex-row sm:justify-end sm:px-7">
        <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2">Cancel</a>
        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 focus-visible:ring-offset-2">Save changes</button>
    </div>
</form>
@endsection
