@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <a class="mb-2 inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 transition hover:text-sky-700" href="{{ route('categories.index') }}">
            <svg aria-hidden="true" class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 12H5m7 7-7-7 7-7" /></svg>
            Back to categories
        </a>
        <p class="mb-2 text-xs font-bold uppercase tracking-[0.18em] text-sky-600">Catalog / Category</p>
        <h1 class="text-3xl font-bold tracking-tight text-slate-950">{{ $category->name }}</h1>
        <p class="mt-2 text-sm text-slate-500">Category details and products</p>
    </div>
    <button type="button" data-modal-open="editCategoryDetailModal" class="inline-flex w-fit items-center gap-2 rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-sky-600/20 transition hover:bg-sky-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 focus-visible:ring-offset-2">
        <svg aria-hidden="true" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m16.862 4.487 1.688-1.688a2.121 2.121 0 0 1 3 3l-9.193 9.193-4.125 1.125 1.125-4.125 7.505-7.505Z" /></svg>
        Edit category
    </button>
</div>

<section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="category-details-heading">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h2 id="category-details-heading" class="text-base font-semibold text-slate-900">About this category</h2>
        <span class="rounded-full bg-sky-50 px-3 py-1 text-sm font-semibold text-sky-800 ring-1 ring-inset ring-sky-200">
            {{ number_format($products->total()) }} {{ \Illuminate\Support\Str::plural('product', $products->total()) }}
        </span>
    </div>
    <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-600">{{ filled($category->description) ? $category->description : 'No description provided.' }}</p>
</section>

<section class="mt-6" aria-labelledby="category-products-heading">
    <div class="mb-4">
        <h2 id="category-products-heading" class="text-lg font-semibold text-slate-900">Products in this category</h2>
        <p class="mt-1 text-sm text-slate-500">Browse the products assigned to {{ $category->name }}.</p>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-[700px] w-full divide-y divide-slate-200 text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th scope="col" class="px-5 py-3.5 font-semibold">Product</th>
                    <th scope="col" class="px-5 py-3.5 font-semibold">SKU</th>
                    <th scope="col" class="px-5 py-3.5 font-semibold">Quantity</th>
                    <th scope="col" class="px-5 py-3.5 font-semibold">Price</th>
                    <th scope="col" class="px-5 py-3.5 text-right font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($products as $product)
                <tr class="transition-colors hover:bg-sky-50/60">
                    <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-900">{{ $product->name }}</td>
                    <td class="whitespace-nowrap px-5 py-4 font-mono text-xs text-slate-600">{{ $product->sku }}</td>
                    <td class="whitespace-nowrap px-5 py-4 text-slate-700">{{ number_format($product->quantity) }}</td>
                    <td class="whitespace-nowrap px-5 py-4 text-slate-700">&#8369;{{ number_format((float) $product->price, 2) }}</td>
                    <td class="whitespace-nowrap px-5 py-4 text-right">
                        <a href="{{ route('products.show', $product->id) }}" class="font-semibold text-sky-700 hover:text-sky-900">View product</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <p class="font-semibold text-slate-900">No products in this category yet</p>
                        <p class="mt-1 text-sm text-slate-500">Products assigned to this category will appear here.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($products->hasPages())
    <div class="mt-5">{{ $products->links() }}</div>
    @endif
</section>

<div id="editCategoryDetailModal" data-modal class="fixed inset-0 z-50 {{ $errors->any() && old('form_type') === 'edit_category_detail' ? 'flex' : 'hidden' }} items-center justify-center bg-slate-950/60 p-4">
    <div class="w-full max-w-lg overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/20">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Edit Category</h2>
                <p class="mt-1 text-sm text-slate-500">Update the category details.</p>
            </div>
            <button type="button" data-modal-close aria-label="Close edit category dialog" class="rounded-xl p-2 text-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500">&times;</button>
        </div>

        <form action="{{ route('categories.update', $category->id) }}" method="POST" class="space-y-5 p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="form_type" value="edit_category_detail">

            <div>
                <label for="detail_category_name" class="mb-1.5 block text-sm font-semibold text-slate-700">Category name</label>
                <input id="detail_category_name" name="name" value="{{ old('name', $category->name) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                @if (old('form_type') === 'edit_category_detail')
                    @error('name') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
                @endif
            </div>

            <div>
                <label for="detail_category_description" class="mb-1.5 block text-sm font-semibold text-slate-700">Description</label>
                <textarea id="detail_category_description" name="description" class="min-h-32 w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">{{ old('description', $category->description) }}</textarea>
                @if (old('form_type') === 'edit_category_detail')
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
