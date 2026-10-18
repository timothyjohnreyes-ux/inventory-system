@extends('layouts.app')

@section('content')

<h1 class="mb-2 text-3xl font-bold tracking-tight text-slate-900">Add Product</h1>

<a class="mb-8 inline-flex text-sm font-semibold text-sky-700 transition hover:text-sky-900" href="{{ route('products.index') }}">Back to Products</a>

<form class="mt-6 max-w-3xl space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8" action="{{ route('products.store') }}" method="POST">

    @csrf

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="name">Product Name</label>
        <input class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-200" type="text" name="name" id="name" value="{{ old('name') }}">

        @error('name')
        <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <br>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="sku">SKU</label>
        <input class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-200" type="text" name="sku" id="sku" value="{{ old('sku') }}" placeholder="SKU-123456"> 

        @error('sku')
        <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <br>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="category_id">Category</label>
        <select class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-200" name="category_id" id="category_id">
            <option value="">Select Category</option>

            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>

        @error('category_id')
        <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <br>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="quantity">Quantity</label>
        <input class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-200" type="number" name="quantity" id="quantity" min="0" value="{{ old('quantity') }}">

        @error('quantity')
        <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <br>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="price">Price (&#8369;)</label>
        <input class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-200" type="number" name="price" id="price" min="0" step="0.01" value="{{ old('price') }}">

        @error('price')
        <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <br>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="description">Description</label>
        <textarea class="min-h-32 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-200" name="description" id="description">{{ old('description') }}</textarea>

        @error('description')
        <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <br>

    <button class="rounded-xl bg-amber-500 px-5 py-3 text-sm font-semibold text-slate-950 shadow-sm transition hover:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2" type="submit">Add Product</button>

</form>

@endsection
